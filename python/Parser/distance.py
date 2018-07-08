import numpy as np
import math
import time
import sys
import pymysql as py

def Distance(a, b, xSize, ySize, Dfunc):
    output = np.zeros(xSize)
    for i in range(xSize):
        output[i]=Dfunc(a[i],b,ySize)
    return np.round(output,3)


def Euclidean(a, b, ySize):
    output=0
    for j in range(ySize):
        output+=(a[j]-b[j])**2
    return math.sqrt(output)


def Cosine(a,b,ySize):
    if np.linalg.norm(a) == 0 or np.linalg.norm(b) == 0:
        return 1
    return 1-np.dot(a,b)/np.linalg.norm(a)/np.linalg.norm(b)


def solve(tf_idf, tf_idf_this, totalNumofDoc, totalNumofWord, num):
    DE = Distance(tf_idf, tf_idf_this, totalNumofDoc, totalNumofWord, Euclidean)
    DC = Distance(tf_idf, tf_idf_this, totalNumofDoc, totalNumofWord, Cosine)

    out_DE = DE.argsort(kind='mergesort')[:num]
    out_DC = DC.argsort(kind='mergesort')[:num]

    #print("Euclidean: {} {} {}".format(out_DE[0] + 1, out_DE[1] + 1, out_DE[2] + 1))
    #print("Cosine: {} {} {}".format(out_DC[0] + 1, out_DC[1] + 1, out_DC[2] + 1))
    return [out_DE[:num], out_DC[:num],[DE[out_DE[0]],DE[out_DE[1]],DE[out_DE[2]]], [DC[[out_DC[0]]], DC[out_DC[1]], DC[out_DC[2]]]]


def findClosest(totalNumofNewDoc, file, num):
    tf_idf = np.load("../python/Parser/tf_idf.npy")
    idf = np.load("../python/Parser/idf.npy")
    dict = np.load("../python/Parser/dict.npy").item()
    temp = np.load("../python/Parser/totalNum.npy")
    linesData = []
    totalNumofDoc, totalNumofWord = temp[0], temp[1]
    w, h = totalNumofWord, totalNumofNewDoc
    output1 = [[0 for y in range(2)] for z in range(h)]
    output2 = [[0 for y in range(2)] for z in range(h)]

    tf = np.zeros((totalNumofNewDoc, totalNumofWord))
    index = 0

    for line in targetFile:
        numOfEmpty = 0
        emptyLine = 0

        while numOfEmpty < len(line):

            if line[numOfEmpty] == '\n' or line[numOfEmpty] == '\r':
                emptyLine = 1
                break

            elif line[numOfEmpty] == ' ':
                numOfEmpty += 1

            else:
                break

        if emptyLine == 1:
            continue

        line = line[numOfEmpty:]
        linesData += [line]
        translation_table = dict.fromkeys(map(ord, ",.:!?'\"\n"), None)
        line = line.translate(translation_table)
        line = line.lower()
        fields = line.split(' ')
        for words in fields:
            if words not in dict:
                continue
            tf[index][dict[words]] += 1
        tf[index] /= len(fields)
        index += 1

    for j in range(totalNumofNewDoc):
        for i in range(totalNumofWord):
            tf[j][i] *= idf[i]
        temp = solve(tf_idf, tf[j], totalNumofDoc, totalNumofWord, num)
        output1[j][0] = temp[0]
        output1[j][1] = temp[1]
        output2[j][0] = temp[2]
        output2[j][1] = temp[3]
    return output1, output2, linesData


def determineLabel(NumofSamples, file, num, minSupp):
    label = np.load("../python/parser/label.npy")
    temp, dist, lines = findClosest(NumofSamples, file, num)
    returnLabels = [0 for i in range(NumofSamples)]
    confidence = [0 for i in range(NumofSamples)]

    for i in range(NumofSamples):
        count = dict()
        for j in range(num):
            label0 = label[temp[i][0][j]]
            label1 = label[temp[i][1][j]]
            if (dist[i][0][j] <= 1):
                if label0 not in count:
                    count[label0] = 1*(1-dist[i][0][j])
                else:
                    count[label0] += 1*(1-dist[i][0][j])

            if (dist[i][1][j] <= 1):
                if label1 not in count:
                    count[label1] = 1*(1-dist[i][1][j])
                else:
                    count[label1] += 1*(1-dist[i][1][j])
        result = sorted(count, key=count.get, reverse=True)
        if float(count[result[0]]) >= minSupp:
            returnLabels[i] = [lines[i], result[0]]
            confidence[i] = float(count[result[0]])
    return returnLabels, confidence


def assignRightThings(labels):
    result = dict()
    index = -1

    for i in labels:
        if i == 0:
            continue
        if i[1] == 'University':
            if 'University' not in result:
                result['University'] = i[0]

        if i[1] == 'Degree Type':
            if 'Major' not in result:
                result['Major'] = i[0]

        if i[1] == 'Minor':
            if 'Minor' not in result:
                result['Minor'] = i[0]

        if i[1] == 'Skill':
            if 'Skill' not in result:
                result['Skill'] = set()
            result['Skill'].add(i[0])

        if i[1] == 'Title':
            index += 1
            if 'Experience' not in result:
                result['Experience'] = []
            result['Experience'] += [dict()]

            result['Experience'][index]['Title'] = i[0]

        if i[1] == 'Date':
            if index< 0:
                continue
            else:
                if 'Date' not in result['Experience'][index]:
                    result['Experience'][index]['Date'] = i[0]

        if i[1] == 'Location':
            if index< 0:
                continue
            else:
                if 'Location' not in result['Experience'][index]:
                    result['Experience'][index]['Location'] = i[0]

        if i[1] == 'Experience Description':
            if index< 0:
                continue
            else:
                if 'Description' not in result['Experience'][index]:
                    result['Experience'][index]['Description'] = []
                result['Experience'][index]['Description'] += [i[0]]
    return result

def updateDb(final,uid):
    db = py.connect('webhost.engr.illinois.edu','jobhunter_whu14','Fuckuiuc12','jobhunter_db')

    cursor = db.cursor()
    cursor.execute("SELECT * FROM `user` WHERE `uid`="+str(uid))
    data = cursor.fetchall()
    school = data[0][6]
    major = data[0][8]
    secondMajor = data[0][9]
    minor = data[0][11]
    GPA = data[0][12]

    for i in final:
        #------User
        if i == 'University' and school == "":
            school = final[i]
        if i == 'Major':
            if major == "":
                major = final[i]
            if secondMajor == "":
                secondMajor = final[i]
        if i == 'Minor' and minor == "":
            minor = final[i]
        if i == 'GPA' and GPA == "":
            GPA = final[i]

        #------Experience
        if i == 'Experience':
            cursor.execute("SELECT MAX(uidExpNum) FROM Experience WHERE uid=" + str(uid))
            data = cursor.fetchall()
            nextIndex = 1 if data[0][0] is None else data[0][0] + 1

            for j in final[i]:
                title = ""
                description = ""
                location = ""
                for k in j:
                    if k == 'Title':
                        title = j[k]
                    if k == 'Location':
                        location = j[k]
                    if k == 'Description':
                        for l in j[k]:
                            description += l
                cursor.execute("INSERT INTO `Experience`(`Title`, `Location`, `Description`, `uidExpNum`, `uid`) VALUES ('{}','{}','{}','{}','{}')".format(title,location,description,nextIndex,uid))
                nextIndex += 1

        #------Skill
        if i == 'Skill':
            cursor.execute("SELECT MAX(skillNum) FROM hasSkill WHERE uid=" + str(uid))
            data = cursor.fetchall()
            nextIndex = 0 if data[0][0] is None else data[0][0]

            for j in final[i]:
                nextIndex += 1
                cursor.execute("INSERT INTO hasSkill(uid, SkillName, skillNum) VALUES ('{}','{}','{}')".format(uid,j,nextIndex))

uid = sys.argv[1]
targetFile = open("../python/"+uid+".txt", 'r')

# parameters CHANGE IT ACCORDINGLY!
num = 3
minSupp = 0.5
NumofSamples = 0

for line in targetFile:
    numOfEmpty = 0
    emptyLine = 0

    while numOfEmpty < len(line):

        if line[numOfEmpty] == '\n' or line[numOfEmpty] =='\r':
            emptyLine = 1
            break

        elif line[numOfEmpty] == ' ':
            numOfEmpty += 1

        else:
            break

    if emptyLine != 1:
        NumofSamples += 1

targetFile.seek(0)
newLabels, confidence = determineLabel(NumofSamples, targetFile, num, minSupp)

final = assignRightThings(newLabels)
print(final)
updateDb(final,uid)



