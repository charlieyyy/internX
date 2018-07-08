import numpy as np
import math
import time
from sklearn import datasets
from sklearn.decomposition import PCA
import sys

def parseLabel(file):
    output = []
    for line in file:
        if line[len(line) - 1] == '\n':
            line = line[:-1]
        output += [line]
    return np.array(output)

start_time = time.time()
data = np.array([])
dict = {}
totalNumofWord = 0
totalNumofDoc = 0
index = 0
file = open(sys.argv[1], 'r')

# calculate number of words
for line in file:
    #print(line)
    totalNumofDoc += 1
    data = np.append(data, [line], axis=0)
    translation_table = dict.fromkeys(map(ord, ",.:!?'\"\n"), None)
    line = line.translate(translation_table)
    line = line.lower()
    fields = line.split(' ')
    for words in fields:
        if (words not in dict):
            dict[words] = totalNumofWord
            totalNumofWord += 1
print(totalNumofDoc, totalNumofWord)

tf = np.zeros((totalNumofDoc, totalNumofWord))
idf = np.zeros(totalNumofWord)
file.seek(0)
for line in file:
    translation_table = dict.fromkeys(map(ord, ",.:!?'\"\n"), None)
    line = line.translate(translation_table)
    line = line.lower()
    fields = line.split(' ')
    for words in fields:
        if (tf[index][dict[words]] == 0):
            idf[dict[words]] += 1
        tf[index][dict[words]] += 1
    tf[index] /= len(fields)
    index += 1

idf = np.log(totalNumofDoc / idf)
for j in range(totalNumofDoc):
    for i in range(totalNumofWord):
        tf[j][i] *= idf[i]
tf_idf = tf

#create label
labelFile = open(sys.argv[2], 'r')
label = parseLabel(labelFile)

#save data
np.save('label',label)
np.save("tf_idf", tf_idf)
np.save("totalNum", np.array([totalNumofDoc, totalNumofWord]))
np.save("dict", dict,)
np.save("idf",idf)
print("--- %s seconds ---\n" % (time.time() - start_time))