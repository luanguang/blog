import math

def double_search(list, item):
    low = 0
    high = len(list) - 1
    while (low <= high):
        mid = math.ceil((low + high) / 2)
        if (list[mid] == item):
            return mid
        if (list[mid] < item):
            low = mid+1
        else:
            high = mid-1
    return None

if (__name__ == '__main__'):
    my_list = [1,2,3,4,5,6,7,8,9,10]
    print(double_search(my_list, 4))