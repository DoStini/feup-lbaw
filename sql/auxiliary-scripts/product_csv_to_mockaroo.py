import csv

name = ''
desc = ''

with open('ikea_names.csv') as csv_file:
    csv_reader = csv.reader(csv_file, delimiter=',')
    header = True
    for row in csv_reader:
        if header:
            header = False
            continue
        name += f"\"{row[0]}\","
        desc += f"\"{row[1]}\","


with open('p_names.txt', 'w') as convert_file:
    convert_file.write(name)

with open('p_desc.txt', 'w') as convert_file:
    convert_file.write(desc)
