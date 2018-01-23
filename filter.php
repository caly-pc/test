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
		$data = 'agenbwaogjoahowngowwj铅弹eaojn3igw393nlnag;efja;熟妇, 我们拥有歌啊叫啊感觉；啊如果后卫；哈；文化顾问哈根；喔和高额逼我我干恶你高傲坚强各种各样的神奇能力, 这是我们的世界, 在政论区, 傲视群雄.
	egjaowegjaw;gaewjn4bhod多噶鞥；王啊日高；我哈围观哇诶和格瓦拉你噶军事委员会武汉年糕为何噶我 根；阿呢；昂 new；根；娃饿噶喝；韩国哇哦；故哈；为福音会';
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
echo $filter->search('agenbwaogjoahowngowwj铅弹eaojn3igw393nlnag;efja;熟妇, 我们拥有歌啊叫啊感觉；啊如果后卫；哈；文化顾问哈根；喔和高额逼我我干恶你高傲坚强各种各样的神奇能力, 这是我们的世界, 在政论区, 傲视群雄.
	egjaowegjaw;gaewjn4bhod多噶鞥；王啊日高；我哈围观哇诶和格瓦拉你噶军事委员会武汉年糕为何噶我 根；阿呢；昂 new；根；娃饿噶喝；韩国哇哦；故哈；为福音会');
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

