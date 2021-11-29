import random
from itertools import product


COLORS = [
    "red",
    "blue",
    "green",
    "black",
    "white",
    "brown"
]

MATERIAL = [
    "glass", 
    "steel", 
    "aluminum", 
    "copper", 
    "brass", 
    "bronze", 
    "iron", 
    "walnut wood", 
    "pine wood", 
    "oak wood", 
    "maple wood", 
    "mahogany wood", 
    "cherry wood", 
    "beech wood", 
    "ash wood", 
    "plastic", 
    "marble", 
    "bamboo", 
    "rattan" 
]

def pick_random_mat():
    l = []
    qnt = random.randint(1, 3)
    for x in range(qnt):
        l.append('"' + MATERIAL[random.randint(1, len(MATERIAL)-1)] + '"')
    return list(dict.fromkeys(l))


def pick_random_color():
    l = []
    qnt = random.randint(1, 3)
    for x in range(qnt):
        l.append('"' + COLORS[random.randint(1, len(COLORS)-1)] + '"')
    return list(dict.fromkeys(l))


curr = ""


f = open("ok.txt", "r")
for x in f:
    color = '"color"' + f":[{','.join(pick_random_color())}],"
    mat = '"material"' + f":[{','.join(pick_random_mat())}]"
    
    res = '{' + color + mat + '}'

    y = x.replace("[]", res)
    print(y[:-1], sep="")






