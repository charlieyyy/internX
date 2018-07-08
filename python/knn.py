#!/home/jobhunter/mypython/bin/python

import pymysql as py
import math
import sys

def getUserFavorite(userid):
    # Open database connection
    db = py.connect('webhost.engr.illinois.edu','jobhunter_whu14','Fuckuiuc12','jobhunter_db')

    # prepare a cursor object using cursor() method
    cursor = db.cursor()

    cursor.execute("SELECT uid,pid FROM `Favorite`")
    data = cursor.fetchall()
    dataOfAllUser = dict()
    dataOfThisUser = set()

    for x in data:
        if x[0] == str(userid):
            dataOfThisUser.add(x[1])
        else:
            if x[0] not in dataOfAllUser:
                dataOfAllUser[x[0]] = set()
                dataOfAllUser[x[0]].add(x[1])
            else:
                dataOfAllUser[x[0]].add(x[1])

    # disconnect from server
    db.close()

    return dataOfThisUser, dataOfAllUser


def cosine(x,y): #calculate cosine distance between two vectors, return similarity
    absx = 0
    absy = 0
    cross = 0
    for i in x:
        absx += 1
        if (i in y):
            cross += 1

    for i in y:
        absy += 1

    absx = math.sqrt(absx)
    absy = math.sqrt(absy)
    return cross/absx/absy


def findKNN(userId,k,dataOfThisUser,dataOfAllUser):
    a, b = dataOfThisUser,dataOfAllUser
    similarity = dict()
    for user in b:
        similarity[user] = cosine(a, b[user])
    temp = sorted(similarity, key=similarity.get)

    return temp[-k:]


def recommend(userId,k,minSupport):
    a, b = getUserFavorite(userId)
    KNNIndex = findKNN(userId,k,a,b)
    recommendList = dict()
    for i in KNNIndex:

        for j in b[i]:

            if j in recommendList:
                recommendList[j] += 1
            else:
                if j not in a:
                    recommendList[j] = 1

    finalRecommend = set()
    for i in recommendList:
        if recommendList[i] >= len(KNNIndex) * minSupport:
            finalRecommend.add(i)
    return finalRecommend

result = recommend(sys.argv[1],int(sys.argv[2]),float(sys.argv[3]))
while(len(result)>0):
    print(result.pop())



