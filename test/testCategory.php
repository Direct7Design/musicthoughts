<?php
require_once 'test.php';

class CategoryTest extends PHPUnit_Framework_TestCase
	{
	public $cat11 = 'big thoughts';

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testCategory11()
		{
		$c = new Category(11);
		$this->assertEquals($this->cat11, $c->description());
		$d = Category::fetch(11);
		$this->assertEquals($this->cat11, $d->description());
		$e = Category::fetch('11');
		$this->assertEquals($this->cat11, $e->description());
		}

	function testCategoryChange()
		{
		$newname = 'grokking';
		$c = new Category(11);
		$this->assertEquals($this->cat11, $c->description());
		$c->set(array('description' => $newname));
		$d = new Category(11);
		$this->assertEquals($newname, $d->description());
		}

	function testCategoryAll()
		{
		$all = Category::all();
		$this->assertEquals(12, count($all));
		$c = $all['11'];
		$this->assertEquals($this->cat11, $c->description());
		}

	# can't test this and Japanese since QQ defines unchangable constants!
	function no_testEnglishSearch()
		{
		# 3       music listening
		$qq = new QQ('en');
		$c = Category::search('listening');
		$this->assertEquals(3, $c->id);
		$d = Category::search('unicorn');
		$this->assertEquals(false, $d);
		}

	function testJapaneseSearch()
		{
		$qq = new QQ('ja');
		$c = Category::search('楽曲');  #1
		$this->assertEquals(1, $c->id);
		$d = Category::search('ユニコーン');
		$this->assertFalse($d);
		}

	function testHowMany()
		{
		$all = Category::all_howmany();
		$c = $all['11'];
		$this->assertEquals('1', $c->howmany());
		}

	function testThoughts()
		{
		$c = new Category(11);
		$thoughts = $c->thoughts('fr');
		$this->assertType('array', $thoughts);
		$t = array_pop($thoughts);
		$this->assertType('Thought', $t);
		$this->assertEquals('Le chocolat est divine.', $t->thought());
		}
	}
?>
