<?php
require_once 'test.php';

class ThoughtTranslationTest extends PHPUnit_Framework_TestCase
	{
	public $en = 'Chocolate is divine.';
	public $fr = 'Le chocolat est divine.';
	public $ja = 'チョコレートの神である';
	public $zh = '中国的网页';

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testTrans3()
		{
		$t = new ThoughtTranslation(3);
		$this->assertEquals($this->ja, $t->thought());
		}

	function testAdd()
		{
		$t = new ThoughtTranslation(false);
		$new_id = $t->add(array('thought_id' => 1, 'lang' => 'zh', 'thought' => $this->zh));
		$t = new ThoughtTranslation(intval($new_id));
		$this->assertEquals($this->zh, $t->thought());
		}

	function testAddThoughtFound()
		{
		$t = new ThoughtTranslation(false);
		$new_id = $t->add(array('thought_id' => 1, 'lang' => 'zh', 'thought' => $this->zh));
		$t = new Thought(1, 'zh');
		$this->assertEquals($this->zh, $t->thought());
		}
	}
?>
