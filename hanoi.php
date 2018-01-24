<?php

class Hanoi {

    private $count = 0;

    private function loop($n, $x, $y, $z)
    {
        if ($n == 1) {
            $this->move($x, 1, $z);
        } else {
            $this->loop($n - 1, $x, $z, $y);
            $this->move($x, $n, $z);
            $this->loop($n - 1, $y, $x, $z);
        }
    }

    private function move($x, $n, $z)
    {
        $this->count++;
        echo "{$this->count} : {$n} 从 {$x} 移到 {$z}<br />";
    }


    public function run($n, $x, $y, $z)
    {
        $this->loop($n, $x, $y, $z);
        echo $this->count;
    }

}

$obj = new Hanoi;
$obj->run(7, 'a', 'b', 'c');