<?php

class Quicksort {

    private $_numbers = [];

    public function __construct(array $numbers)
    {
        foreach ($numbers as $key => $value) {
            $numbers[$key] = intval($value);
        }

        $this->_numbers = $numbers;
    }

    private function _process($left, $right)
    {
        if ($left >= $right) return; 

        $temp = $this->_numbers[$left];

        $l = $left;
        $r = $right;

        while ($l < $r) {

            while ($this->_numbers[$r] >= $temp && $l < $r) {
                $r--;
            }

            while ($this->_numbers[$l] <= $temp && $l < $r) {
                $l++;
            }
            
            $t = $this->_numbers[$l];
            $this->_numbers[$l] = $this->_numbers[$r];
            $this->_numbers[$r] = $t;
        }


        $this->_numbers[$left] = $this->_numbers[$l];
        $this->_numbers[$l] = $temp;

        $this->_process($left, $l - 1);
        $this->_process($l + 1, $right);
    }

    public function run()
    {
        echo implode(',', $this->_numbers);
        echo "<br />";

        $this->_process(0, count($this->_numbers) - 1);

        echo implode(',', $this->_numbers);
    }
}

$numbers = [5,6,5,1,3,2,6,9,7,8,7,1];

$quicksort = new Quicksort($numbers);
$quicksort->run();