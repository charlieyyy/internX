# all of the inclusions
import argparse
import csv
import functools
import glob
import os
import re
import sys
import nltk
import pyap
import time
reload(sys)
sys.setdefaultencoding('utf8')

import pymysql as py

from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams
from pdfminer.pdfinterp import PDFResourceManager, PDFPageInterpreter
from pdfminer.pdfpage import PDFPage
from cStringIO import StringIO
from nltk.tag import StanfordNERTagger


def main():
    """
    calls all of the utilities to parse the resume
    """
    print("start parsing")

    userid = sys.argv[1]

    # Loop through all pdf files
    print('parsing:' + userid)
    text = readAndConvert(userid)
    text_file = open("../python/"+userid+".txt", "w")

    # !!!!!!!!!! UNMARK BELOW TO CHANGE THE PATH ON SERVER !!!!!!!!!!
    # text_file = open("./"+userid+".txt", "w"

    text_file.write(text)
    text_file.close()
    basicInfoParser(text[0:200])

    print("end parsing")


def readAndConvert(input_pdf_path):
    """
    read and convert all of local pdf to an array of strings
    """
    rsrcmgr = PDFResourceManager()
    retstr = StringIO()
    codec = 'utf-8'
    laparams = LAParams()
    device = TextConverter(rsrcmgr, retstr, codec=codec, laparams=laparams)
    interpreter = PDFPageInterpreter(rsrcmgr, device)
    password = ""
    maxpages = 0
    caching = True
    pagenos = set()
    real_path = "./resumes/"+input_pdf_path+".pdf"

    # !!!!!!!!!! UNMARK BELOW TO CHANGE THE PATH ON SERVER !!!!!!!!!!
    # real_path = "../php/resumes/"+input_pdf_path+".pdf"


    # Iterate through pages
    path_open = file(real_path, 'rb')
    for page in PDFPage.get_pages(path_open, pagenos, maxpages=maxpages, password=password,caching=caching, check_extractable=True):
        interpreter.process_page(page)
    path_open.close()
    device.close()

    # Get full string from PDF
    full_string = retstr.getvalue()
    retstr.close()

    # Normalize a bit, removing line breaks
    # full_string = full_string.replace("\r", "\n")
    # full_string = full_string.replace("\n", " ")

    # Remove awkward LaTeX bullet characters
    full_string = re.sub(r"\(cid:\d{0,2}\)", " ", full_string)
    return full_string.encode('ascii', errors='ignore')


def basicInfoParser(text):
    """
    Parse Name, Number, Email and Address
    """
    # Open database connection
    db = py.connect('webhost.engr.illinois.edu','jobhunter_whu14','Fuckuiuc12','jobhunter_db')

    # prepare a cursor object using cursor() method
    cursor = db.cursor()

    userid = sys.argv[1]

    # ---------- Name Part ----------
    firstName = ''
    middleName = ''
    lastName = ''
    st = StanfordNERTagger('../python/stanford-ner-2016-10-31/classifiers/english.all.3class.nodistsim.crf.ser.gz','../python/stanford-ner-2016-10-31/stanford-ner.jar')

    # !!!!!!!!!! UNMARK BELOW TO CHANGE THE PATH ON SERVER !!!!!!!!!!
    # st = StanfordNERTagger('./stanford-ner-2016-10-31/classifiers/english.all.3class.nodistsim.crf.ser.gz','./stanford-ner-2016-10-31/stanford-ner.jar')


    i = 0
    flag = 0

    for sent in nltk.sent_tokenize(text):
        tokens = nltk.tokenize.word_tokenize(sent)
        tags = st.tag(tokens)
        for tag in tags:
            # print tag
            if tag[1]=='PERSON':
                i = i+1
                if i==1:
                    firstName = tag[0]
                if i==2:
                    if tag[0].find('.')==-1:
                        middleName = 'NA'
                        lastName = tag[0]
                        break
                    else:
                        middleName = tag[0]
                        flag = 1
                if i==3 and flag==1:
                    resume_summary['last_name'] = [tag[0]]
                    break


    # ---------- Number ----------
    phoneNumber = ''
    try:
        regular_expression = re.compile(r"(\(?)"  # open parenthesis
                                        r"(\d{3})?"  # area code
                                        r"(\)?)"  # close parenthesis
                                        r"[\s\.-]{0,2}?"  # area code, phone separator
                                        r"(\d{3})"  # 3 digit exchange
                                        r"[\s\.-]{0,2}"  # separator bbetween 3 digit exchange, 4 digit local
                                        r"(\d{4})",  # 4 digit local
                                        re.IGNORECASE)
        result = re.search(regular_expression, text)
        if result:
            result = result.groups()
            result = "".join(result)
            phoneNumber = result
        if !result:
            regular_expression = re.compile(r"(\d{3})?"r"[\s\.-]{0,2}?"r"(\d{3})"r"[\s\.-]{0,2}"r"(\d{4})",re.IGNORECASE)
            result = re.search(regular_expression, text)
            if result:
                result = result.groups()
                result = "".join(result)
                phoneNumber = result
        if !result:
            regular_expression = re.compile(r"(\d{3})?"  # area code
                                            r"[\s\.-]{0,2}?"  # area code, phone separator
                                            r"(\d{3})"  # 3 digit exchange
                                            r"[\s\.-]{0,2}"  # separator bbetween 3 digit exchange, 4 digit local
                                            r"(\d{4})",  # 4 digit local
                                            re.IGNORECASE)
            result = re.search(regular_expression, text)
            if result:
                result = result.groups()
                result = "".join(result)
                phoneNumber = result
    except Exception, exception_instance:
        print('Issue parsing phone number: ' + text + str(exception_instance))


    # ---------- Address ----------
    addressSQL = ''
    addresses = pyap.parse(text, country = 'US')
    for address in addresses:
        addressSQL = str(address)
        break
    sqlq = "UPDATE user SET `Address` = \'" + addressSQL + "\', `Phone Number` = " + phoneNumber + " WHERE `uid` = \'" + userid + "\'"

    # update sql
    cursor.execute(sqlq)



# call the main function
main()
