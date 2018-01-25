package main

import (
    "fmt"
//    "os"
//    "strconv"
)

func printData(values []int) {
    for _, val := range values {
        fmt.Println(val)
    }
}

//桶排序
func bucketSort(values []int, max int) {
    bucket := make([]int, max + 1)

    for _, val := range values {
        bucket[val]++
    }

    for key, val := range bucket {
        for i := 1; i <= val; i++ {
            fmt.Println(key)
        }
    }
}

//冒泡排序
func bubbleSort(values []int) {
    for i := 0; i < len(values) - 1; i++ {
        for j := len(values) - 1; j > i; j-- {
            if values[i] > values[j] {
                values[i], values[j] = values[j], values[i]
            }
        }
    }

    printData(values)
}

//冒泡排序优化
func optimizeBubbleSort(values []int) {
    flag := true
    for i := 0; i < len(values) - 1; i++ {
        for j := i; j < len(values) - 1; j++ {
            if values[j] > values[j + 1] {
                values[j], values[j + 1] = values[j + 1], values[j]
                flag = false
            }
        }

        if flag {
            break
        }
    }

    printData(values)
}

//选择排序
func selectionSort(values []int) {
    minIndex := 0
    for i := 0; i < len(values); i++ {
        minIndex = i

        for j := i + 1; j < len(values); j++ {
            if values[minIndex] > values[j] {
                minIndex = j
            }
        }

        if minIndex != i {
            values[i], values[minIndex] = values[minIndex], values[i]
        }
    }

    printData(values)
}

func quickSortScript(values []int, left int, right int) {
    if left >= right {
        return
    }

    temp := values[left]
    i, j := left, right

    for i < j {
        //先从右往左查找
        for i < j && values[j] >= temp {
            j--
        }

        for i < j && values[i] <= temp {
            i++
        }
        
        if (i < j) {
            values[i], values[j] = values[j], values[i]
        }
    }

    values[left], values[i] = values[i], values[left]

    quickSortScript(values, left, i - 1)
    quickSortScript(values, i + 1, right)
}

//快速排序
func quickSort(values []int) {
    quickSortScript(values, 0, len(values) - 1)

    printData(values)
}

func mergeScript(left , right []int) []int {
    i, j := 0, 0
    result := []int {}
    for i < len(left) && j < len(right) {
        if left[i] < right[j] { 
            result = append(result, left[i])
            i++
        } else {
            result = append(result, right[j])
            j++
        }
    }

    if i < len(left) {
        for _, val := range left[i:] {
            result = append(result, val)
        }
    }

    if j < len(right) {
        for _, val := range right[j:] {
            result = append(result, val)
        }
    }

    return result
}

func merge(values []int) []int {
    if len(values) <= 1 {
        return values
    }

    num := len(values) >> 1

    left := merge(values[:num])
    right := merge(values[num:])

    return mergeScript(left, right)
}

func mergeSort(values []int) {
    printData(merge(values))
}


func binarySearch(numbers []int, value int) int{
    left := 0
    right := len(numbers) - 1
    mid := 0

    for left <= right {
        mid = (left + right) >> 1

        if numbers[mid] == value {
            return mid
        } else if value < numbers[mid] {
            right = mid - 1
        } else {
            left = mid + 1
        }
    }

    return -1
}

func main() {
    /*
    args := os.Args

    if args == nil || len(args) < 2 {
        fmt.Println("Please input number")
        return
    }

    max := 1
    list := []int {}
    for i := 1; i < len(args); i++ {
        number, _ := strconv.Atoi(args[i])
        list = append(list, number)

        if number > max {
            max = number
        }
    }

    fmt.Println("bucket sort")
    bucketSort(list, max)

    fmt.Println("bubble sort")
    bubbleSort(list)

    fmt.Println("optimize bubble sort")
    optimizeBubbleSort(list)

    fmt.Println("selection sort")
    selectionSort(list)

    fmt.Println("merge sort")
    mergeSort(list)

    fmt.Println("quick sort")
    quickSort(list)
    */

    numbers := []int {1, 4, 5, 7, 8, 9}
    fmt.Println("binary search")
    fmt.Println(binarySearch(numbers, 4))
    fmt.Println(binarySearch(numbers, 8))
}