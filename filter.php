<?php

class filter
{
	private $tree = [];
	private $startTime = 0;

	public function insert($string)
	{
		$word = $this->process($string);

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

	public function search($string)
	{
		$this->beginTime();

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
				} else {
					//$newStr .= $tempStr . $word[$current];
					if ($times) {
						$current -= $times;
						$newStr .= $word[$current];
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
		} else {
			$newStr .= $tempStr;
		}

		echo '查询敏感词消耗的时间: '. $this->getTime() .'<br />';

		return $newStr;
	}

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

		echo '构建Trie树消耗的时间: '. $this->getTime() .', 根元素共有'. count($this->tree) .'个<br />';
	}

	private function process($string)
	{
		preg_match_all('/./u', $string, $data);
		return $data[0];
	}

	private function beginTime()
	{
		$this->startTime = microtime();
	}

	private function getTime()
	{
		return microtime() - $this->startTime;
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

$filter = new filter;

$filter->loadFile('minganciku.php');
$filter->loadFile('badwords.php');
echo $filter->search('我们去上学，小鸟在唱歌！');
echo '<br />';

$filter->test();

/*
$content = file_get_contents('badwords.txt');
$lines = explode('|', $content);

foreach($lines as $line)
{
    file_put_contents('badwords.php', $line .PHP_EOL, FILE_APPEND);
}
echo 'over';
*/

