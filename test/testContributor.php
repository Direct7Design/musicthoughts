<?php
require_once 'test.php';

class ContributorTest extends PHPUnit_Framework_TestCase
	{
	public $name1 = 'Derek Sivers';

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testContributor1()
		{
		$c = new Contributor(1);
		$this->assertEquals($this->name1, $c->name());
		$d = Contributor::fetch(1);
		$this->assertEquals($this->name1, $d->name());
		$e = Contributor::fetch('1');
		$this->assertEquals($this->name1, $e->name());
		$f = Contributor::fetch(834148);
		$this->assertFalse($f);
		}

	function testContributorChange()
		{
		$newname = 'grokking';
		$c = new Contributor(1);
		$this->assertEquals($this->name1, $c->name());
		$c->set(array('name' => $newname));
		$d = new Contributor(1);
		$this->assertEquals($newname, $d->name());
		}

	function testContributorAll()
		{
		$all = Contributor::all();
		$this->assertEquals(1, count($all));
		$c = $all['1'];
		$this->assertEquals($this->name1, $c->name());
		}

	function testSearch()
		{
		$c = Contributor::search('sivers');
		$this->assertEquals($this->name1, $c->name());
		$c = Contributor::search('doogie howser');
		$this->assertFalse($c);
		}

	function testHowMany()
		{
		$all = Contributor::all_howmany();
		$c = $all['1'];
		$this->assertEquals('1', $c->howmany());
		}

	function testThoughts()
		{
		$c = new Contributor(1);
		$thoughts = $c->thoughts('fr');
		$this->assertType('array', $thoughts);
		$t = array_pop($thoughts);
		$this->assertType('Thought', $t);
		$this->assertEquals('Le chocolat est divine.', $t->thought());
		}

	function testAdd()
		{
		$newname = 'Gollum';
		$x = new Contributor(false);
		$new_id = $x->add(array('name' => $newname));
		$x = new Contributor(intval($new_id));
		$this->assertEquals($newname, $x->name());
		}

	function testAddTooBig()
		{
		$newname = 'Kim Kardashian celebrated her 28th birthday at Club LAX in Vegas last night. Her sisters were of course there, and can you believe Kourtney had the nerve to show up looking way hotter than Kim? Billy Ray laid it all out for Miley that her career hinges on her ability to sell Hannah Montana dolls to folks in the Bible Belt, so she better fly straight.';
		$x = new Contributor(false);
		$new_id = $x->add(array('name' => $newname));
		$x = new Contributor(intval($new_id));
		$this->assertEquals(mb_substr($newname, 0, 127), $x->name());
		$newname = '浦和は２５日、チーム批判ととれる発言をしたＦＷ永井に対し、厳重注意と、新潟戦のメンバーから外す処分を下した。練習前にエンゲルス監督と強化幹部が事情聴取した上で決定した。エンゲルス監督は「戦力としては大事だが、タイミングが良くない。永井も納得している」と説明。監督、強化幹部に謝罪した永井は「大事な時期にチームに迷惑をかけてしまった。もうクラブ、監督に（不満を）言うことはなくなると思う」と改心した様子だった。(スポーツニッポン)';
		$x = new Contributor(false);
		$new_id = $x->add(array('name' => $newname));
		$x = new Contributor(intval($new_id));
		$this->assertEquals(mb_substr($newname, 0, 127), $x->name());
		}

	# FATAL:  database "people_test" does not exist. Maybe some day...
	function TODO_testPerson()
		{
		$c = new Contributor(1);
		$p = $c->person();
		$this->assertType('Person', $p);
		$this->assertEquals(1, $p->id);
		}
	}
?>
