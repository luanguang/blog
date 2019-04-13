def sum(arr):
    if len(arr) == 0:
        return 0
    return arr[0] + sum(arr[1:])

def quicksort(array):
    if len(array) < 2:
        return array
    else:
        pivot = array[0]
        less = [i for i in array[1:] if i <= pivot]
        greater = [i for i in array[1:] if i > pivot]
        return quicksort(less) + [pivot] + quicksort(greater)

if __name__ == '__main__':
    arr = [10,2,5,7]
    print(sum(arr))
    print(quicksort(arr))