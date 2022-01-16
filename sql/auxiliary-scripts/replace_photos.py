import re

f = open("photos.sql", "r")
for x in f:
    if ("person" in x):
        a = int(re.search(r'\d+', x).group())
        new = a % 8 + 1
        print(x.replace(str(a), str(new) + ".jpg")[:-1], sep="")
    if ("product" in x):
        good = x.split("_")[0]
        bad = x.split("_")[1]
        a = int(re.search(r'\d+', good).group())
        b = int(re.search(r'\d+', bad).group())
        new = (a + b) % 15 + 1
        print((good.replace(str(a), str(new) + ".jpg") + "');"), sep="")
    # else:
    #     print(x)
