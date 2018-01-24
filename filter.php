<?php
class filter
{
    private $tree = [];         //trie树
    private $startTime = 0;     //开始时间
    private $endTime = 0;       //结束时间
    private $strMatch = [];    //匹配到的字符串

    /**
     * 插入
     *
     * @param string $string 关键词
     * @return null
     */
    public function insert($string)
    {
        $word = $this->process($string);
        if (count($word) < 2) return;

        $temp = null;
        foreach ($word as $value) {
            if ( is_null($temp) ) {
                if ( key_exists($value, $this->tree) ) {
                    $temp =& $this->tree[$value];
                } else {
                    $this->tree[$value] = [];
                    $temp =& $this->tree[$value];
                }
            } else {
                if ( key_exists($value, $temp) ) {
                    $temp =& $temp[$value];
                } else {
                    $temp[$value] = [];
                    $temp =& $temp[$value];
                }
            }
        }
        $temp['over'] = 1;
    }

    /**
     * 检索
     *
     * @param string $string 要过滤的字符串
     * @return string
     */
    public function search($string)
    {
        $this->beginTime();
        $this->strMatch = array();

        $word = $this->process($string);
        $newStr = '';
        $tempStr = '';
        $times = 0;
        $current = 0;
        $length = count($word);
        $temp = $this->tree;
        for ($current; $current < $length; $current++) {
            if ( key_exists($word[$current], $temp) ) {
                $temp = $temp[$word[$current]];
                $tempStr .= $word[$current];
                $times ++;
            } else {
                if ( !empty($temp['over']) ) {
                    $newStr .= '***';
                    $current --;

                    $this->strMatch[] = $tempStr;
                } else {
                    if ($times) {
                        $current -= $times; //回退，从第二个字符开始重新匹配
                        $newStr .= $word[$current]; //将第一个字符加入新字符串
                    } else {
                        $newStr .= $word[$current];
                    }
                }

                $times = 0;
                $tempStr = '';
                $temp = $this->tree;
            }
        }

        if ( !empty($temp['over']) ) {
            $newStr .= '***';

            $this->strMatch[] = $tempStr;
        } else {
            $newStr .= $tempStr;
        }

        $this->overTime();

        return $newStr;
    }

    public function save()
    {
        return file_put_contents('keywords.json', json_encode($this->tree));
    }

    public function init()
    {
        $this->tree = json_decode(file_get_contents('keywords.json'), true);
    }

    /**
     * 从文件读取数据构建trie树
     *
     * @param string $dir 文件所在地址
     * @return null
     */
    public function loadFile($dir)
    {
        $this->beginTime();
        if ( is_file($dir) ) {
            $lines = file($dir);
            echo '共有'. count($lines) .'个敏感词汇<br />';
            foreach($lines as $line)
            {
                $this->insert( trim($line) );
            }
        }
        $this->overTime();
        echo '构建Trie树消耗的时间: '. $this->getTime() .', 根元素共有'. count($this->tree) .'个<br />';
    }

    private function process($string)
    {
        preg_match_all('/./u', $string, $data);
        return $data[0];
    }

    public function beginTime()
    {
        $this->startTime = microtime();
    }

    public function overTime()
    {
        $this->endTime = microtime();
    }

    public function getTime()
    {
        return $this->endTime - $this->startTime;
    }

    public function getStringMatch()
    {
        return $this->strMatch;
    }

    public function test()
    {
        $data = '我们去上学，小鸟在唱歌！';
        $a = file('minganciku.php');
        $b = file('badwords.php');
        $lines = array_merge($a, $b);
        foreach ($lines as &$line) {
            $line = trim($line);
        }
        
        $this->beginTime();
        echo str_replace($lines, '***', $data), '<br />';
        echo '测试时间: '. $this->getTime() .'<br />';
    }
}

/*
$filter = new filter;
$filter->loadFile('minganciku.php');
$filter->loadFile('badwords.php');
echo $filter->search('我们去上学，小鸟在唱歌！');
echo '<br />';
$filter->test();


$content = file_get_contents('badwords.txt');
$lines = explode('|', $content);
foreach($lines as $line)
{
    file_put_contents('badwords.php', $line .PHP_EOL, FILE_APPEND);
}
echo 'over';
*/