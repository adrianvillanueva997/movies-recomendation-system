from math import sqrt
from scipy.stats import pearsonr

list1 = [3, 2, 5, 4, 3]
list2 = [5, 3, 1, 2, 1]

if len(list1) == len(list2):
    mean1 = sum(list1) / len(list1)
    mean2 = sum(list2) / len(list2)
    a = 0
    bx = 0
    by = 0
    for i in range(len(list1)):
        x_val = list1[i] - mean1
        y_val = list2[i] - mean2
        a += x_val * y_val
        bx += x_val ** 2
        by += y_val ** 2
    b = sqrt(bx * by)
    result = a / b
    print(result)
    print(pearsonr(list1, list2))
else:
    print('He petao')
