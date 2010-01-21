<?php
require_once 'test.php';

# separate tests since ThoughtTest was big, this works with static/groups of thoughts
class ThoughtsTest extends PHPUnit_Framework_TestCase
	{
	public $en = 'Chocolate is divine.';
	public $fr = 'Le chocolat est divine.';
	public $ja = 'チョコレートの神である';

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testAll()
		{
		$a = Thought::all('fr');
		$this->assertType('array', $a);
		$t = $a['1'];
		$this->assertEquals($this->fr, $t->thought());
		}

	# someday when I have enough fixtures to do offset
	function TODO_testAllOffset()
		{
		$a = Thought::all('en', 'DESC', 1, 2);
		$t = $a['3'];
		$this->assertEquals('something else', $t->thought());
		}

	function testInCategory()
		{
		$a = Thought::in_category(11, 'ja');
		$this->assertType('array', $a);
		$this->assertEquals(1, count($a));
		$t = $a['1'];
		$this->assertEquals($this->ja, $t->thought());
		}

	function testForAuthor()
		{
		$a = Thought::for_author(1, 'ja');
		$this->assertType('array', $a);
		$this->assertEquals(1, count($a));
		$t = $a['1'];
		$this->assertEquals($this->ja, $t->thought());
		}

	function testForContributor()
		{
		$a = Thought::for_contributor(1, 'fr');
		$this->assertType('array', $a);
		$this->assertEquals(1, count($a));
		$t = $a['1'];
		$this->assertEquals($this->fr, $t->thought());
		}

	function testNewest()
		{
		$a = Thought::newest(1, 'fr');
		$this->assertType('array', $a);
		$t = $a['1'];
		$this->assertEquals($this->fr, $t->thought());
		}

	function testSearch4()
		{
		$a = Thought::search4('chocolate', 'en');
		$this->assertType('array', $a);
		$t = $a['1'];
		$this->assertEquals($this->en, $t->thought());
		$a = Thought::search4('gerbil', 'ja');
		$this->assertType('array', $a);
		$this->assertEquals(0, count($a));
		}

	function testSearchThought()
		{
		$s = Thought::search('chocolate', 'en');
		$this->assertType('array', $s);
		$this->assertArrayHasKey('MUSICTHOUGHTS', $s);
		$this->assertArrayNotHasKey('AUTHOR', $s);
		$this->assertArrayNotHasKey('CONTRIBUTOR', $s);
		$a = $s['MUSICTHOUGHTS'];
		$this->assertType('array', $a);
		$t = $a['1'];
		$this->assertEquals($this->en, $t->thought());
		}

	function testSearchAuthor()
		{
		$s = Thought::search('wonka', 'ja');
		$this->assertType('array', $s);
		$this->assertArrayNotHasKey('MUSICTHOUGHTS', $s);
		$this->assertArrayHasKey('AUTHOR', $s);
		$this->assertArrayNotHasKey('CONTRIBUTOR', $s);
		$a = $s['AUTHOR'];
		$this->assertType('array', $a);
		$t = $a['1'];
		$this->assertEquals($this->ja, $t->thought());
		}

	function testSearchContributor()
		{
		$s = Thought::search('derek', 'fr');
		$this->assertType('array', $s);
		$this->assertArrayNotHasKey('MUSICTHOUGHTS', $s);
		$this->assertArrayNotHasKey('AUTHOR', $s);
		$this->assertArrayHasKey('CONTRIBUTOR', $s);
		$a = $s['CONTRIBUTOR'];
		$this->assertType('array', $a);
		$t = $a['1'];
		$this->assertEquals($this->fr, $t->thought());
		}

	function testUnapproved()  # TODO: example?
		{
		$a = Thought::unapproved();
		$this->assertType('array', $a);
		$this->assertEquals(0, count($a));
		}
	}
?>
