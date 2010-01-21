<?php
require_once 'test.php';

class AuthorTest extends PHPUnit_Framework_TestCase
	{
	public $name1 = 'Willy Wonka';

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testAuthor1()
		{
		$c = new Author(1);
		$this->assertEquals($this->name1, $c->name());
		$d = Author::fetch(1);
		$this->assertEquals($this->name1, $d->name());
		$e = Author::fetch('1');
		$this->assertEquals($this->name1, $e->name());
		$f = Author::fetch(834148);
		$this->assertFalse($f);
		}

	function testAuthorChange()
		{
		$newname = 'grokking';
		$c = new Author(1);
		$this->assertEquals($this->name1, $c->name());
		$c->set(array('name' => $newname));
		$d = new Author(1);
		$this->assertEquals($newname, $d->name());
		}

	function testAuthorAll()
		{
		$all = Author::all();
		$this->assertEquals(1, count($all));
		$c = $all['1'];
		$this->assertEquals($this->name1, $c->name());
		}

	function testSearch()
		{
		$c = Author::search('wonka');
		$this->assertEquals(1, $c->id);
		$d = Author::search('unicorn');
		$this->assertFalse($d);
		}

	function testHowMany()
		{
		$all = Author::all_howmany();
		$c = $all['1'];
		$this->assertEquals('1', $c->howmany());
		}

	function testThoughts()
		{
		$c = new Author(1);
		$thoughts = $c->thoughts('ja');
		$this->assertType('array', $thoughts);
		$t = array_pop($thoughts);
		$this->assertType('Thought', $t);
		$this->assertEquals('チョコレートの神である', $t->thought());
		}

	function testAdd()
		{
		$newname = 'Gollum';
		$x = new Author(false);
		$new_id = $x->add(array('name' => $newname));
		$x = new Author(intval($new_id));
		$this->assertEquals($newname, $x->name());
		}

	function testAddTooBig()
		{
		$newname = 'Kim Kardashian celebrated her 28th birthday at Club LAX in Vegas last night. Her sisters were of course there, and can you believe Kourtney had the nerve to show up looking way hotter than Kim? Billy Ray laid it all out for Miley that her career hinges on her ability to sell Hannah Montana dolls to folks in the Bible Belt, so she better fly straight.';
		$x = new Author(false);
		$new_id = $x->add(array('name' => $newname));
		$x = new Author(intval($new_id));
		$this->assertEquals(mb_substr($newname, 0, 127), $x->name());
		$newname = '浦和は２５日、チーム批判ととれる発言をしたＦＷ永井に対し、厳重注意と、新潟戦のメンバーから外す処分を下した。練習前にエンゲルス監督と強化幹部が事情聴取した上で決定した。エンゲルス監督は「戦力としては大事だが、タイミングが良くない。永井も納得している」と説明。監督、強化幹部に謝罪した永井は「大事な時期にチームに迷惑をかけてしまった。もうクラブ、監督に（不満を）言うことはなくなると思う」と改心した様子だった。(スポーツニッポン)';
		$x = new Author(false);
		$new_id = $x->add(array('name' => $newname));
		$x = new Author(intval($new_id));
		$this->assertEquals(mb_substr($newname, 0, 127), $x->name());
		}
	}
?>
