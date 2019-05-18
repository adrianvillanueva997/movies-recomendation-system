for k in range(0, len(ratings_list1)):
    numerator += (ratings_list1[k] * ratings_list2[k])
    denominator_1 += ratings_list1[k] * ratings_list1[k]
    denominator_2 += ratings_list2[k] * ratings_list2[k]
similitude = numerator / (sqrt(denominator_1) * sqrt(denominator_2))