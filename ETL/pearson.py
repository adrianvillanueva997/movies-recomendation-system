from math import sqrt

list1 = [3, 2, 5, 4, 3]
list2 = [5, 3, 1, 2, 1]


def pearson(list1, list2, mean1, mean2):
    if len(list1) == len(list2):
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
        return result
    else:
        print('He petao')
