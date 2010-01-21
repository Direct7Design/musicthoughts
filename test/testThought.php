<?php
require_once 'test.php';

class ThoughtTest extends PHPUnit_Framework_TestCase
	{
	public $url = 'http://chocolate.org';
	public $en = 'Chocolate is divine.';
	public $fr = 'Le chocolat est divine.';
	public $ja = 'チョコレートの神である';

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testThoughtBasics()
		{
		$c = new Thought(1, 'en');
		$this->assertEquals($this->url, $c->source_url());
		$d = Thought::fetch(1, 'en');
		$this->assertEquals($this->url, $d->source_url());
		$e = Thought::fetch('1', 'en');
		$this->assertEquals($this->url, $e->source_url());
		$f = Thought::fetch(834148, 'en');
		$this->assertFalse($f);
		}

	function testThoughtChange()
		{
		$newurl = 'http://chocolate.net';
		$c = new Thought(1);
		$this->assertEquals($this->url, $c->source_url());
		$c->set(array('source_url' => $newurl));
		$d = new Thought(1);
		$this->assertEquals($newurl, $d->source_url());
		}

	function testAddTranslation()
		{
		$big = '浦和は２５日、チーム批判ととれる発言をしたＦＷ永井に対し、厳重注意と、新潟戦のメンバーから外す処分を下した。練習前にエンゲルス監督と強化幹部が事情聴取した上で決定した。エンゲルス監督は「戦力としては大事だが、タイミングが良くない。永井も納得している」と説明。監督、強化幹部に謝罪した永井は「大事な時期にチームに迷惑をかけてしまった。もうクラブ、監督に（不満を）言うことはなくなると思う」と改心した様子だった。(スポーツニッポン)';
		$x = new Thought(false);
		$new_id = $x->add2(array('author_id' => 1, 'contributor_id' => 1, 'thought' => $big, 'lang' => 'ja'));
		$x = new Thought(intval($new_id), 'ja');
		$this->assertEquals($big, $x->thought());
		}

	function testChopURL()
		{
		$big = '浦和は２５日、チーム批判ととれる発言をしたＦＷ永井に対し、厳重注意と、新潟戦のメンバーから外す処分を下した。練習前にエンゲルス監督と強化幹部が事情聴取した上で決定した。エンゲルス監督は「戦力としては大事だが、タイミングが良くない。永井も納得している」と説明。監督、強化幹部に謝罪した永井は「大事な時期にチームに迷惑をかけてしまった。もうクラブ、監督に（不満を）言うことはなくなると思う」と改心した様子だった。(スポーツニッポン)';
		$x = new Thought(false);
		$new_id = $x->add2(array('author_id' => 1, 'contributor_id' => 1, 'source_url' => $big, 'thought' => $big, 'lang' => 'ja'));
		$x = new Thought(intval($new_id), 'ja');
		$this->assertEquals(mb_substr($big, 0, 254), $x->source_url());
		}

	function testTransGet()
		{
		$x = new Thought(1, 'en');
		$this->assertEquals($this->en, $x->thought());
		$x = new Thought(1, 'fr');
		$this->assertEquals($this->fr, $x->thought());
		$x = new Thought(1, 'ja');
		$this->assertEquals($this->ja, $x->thought());
		}

	function testTransFallback()
		{
		$x = new Thought(1, 'es');
		$this->assertEquals($this->en, $x->thought());
		}

	function testCategories()
		{
		$x = new Thought(1, 'en');
		$cats = $x->categories();
		$this->assertType('array', $cats);
		$this->assertType('Category', $cats['5']);
		$this->assertType('Category', $cats['11']);
		}

	function testDeleteCategories()
		{
		$x = new Thought(1, 'en');
		$x->delete_categories();
		$cats = $x->categories();
		$this->assertType('array', $cats);
		$this->assertEquals(0, count($cats));
		}

	function testNewCategories()
		{
		$x = new Thought(1, 'en');
		$x->delete_categories();
		$x->add_categories(array(4, 6, 8));
		$cats = $x->categories();
		$this->assertEquals(3, count($cats));
		$this->assertType('Category', $cats['8']);
		}

	function testIntro()
		{
		$x = new Thought(1, 'en');
		$this->assertEquals('Chocolate is...', $x->intro(2));
		$x = new Thought(1, 'fr');
		$this->assertEquals('Le chocolat...', $x->intro(2));
		$x = new Thought(1, 'ja');
		$this->assertEquals('チョ...', $x->intro(2));
		}

	function testRand()
		{
		$x = Thought::random1('en');
		$this->assertEquals($this->en, $x->thought());
		$x = Thought::random1('fr');
		$this->assertEquals($this->fr, $x->thought());
		$x = Thought::random1('ja');
		$this->assertEquals($this->ja, $x->thought());
		}

	function testTranslations()
		{
		$x = new Thought(1);
		$tt = $x->translations();
		$this->assertEquals(3, count($tt));
		$t = $tt['1'];
		$this->assertType('ThoughtTranslation', $t);
		$this->assertEquals($this->en, $t->thought());
		$t = $tt['2'];
		$this->assertEquals($this->fr, $t->thought());
		$t = $tt['3'];
		$this->assertEquals($this->ja, $t->thought());
		}

	function testAuthor()
		{
		$t = new Thought(1);
		$a = $t->author();
		$this->assertEquals('Willy Wonka', $a->name());
		}

	function testContributor()
		{
		$t = new Thought(1);
		$c = $t->contributor();
		$this->assertEquals('Derek Sivers', $c->name());
		}
	}
?>
