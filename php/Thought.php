<?php
/*
-------------------+------------------------+-------------------------------------------------------------
 id             | integer                | not null default nextval('thoughts_id_seq'::regclass)
 approved       | boolean                | not null default false
 author_id      | integer                | not null
 contributor_id | integer                | not null
 created_at     | date                   | not null default ('now'::text)::date
 as_rand        | boolean                | not null default false
 source_url     | character varying(255) | 
-------------------- thought_translations:
 thought_id | integer       | not null
 lang       | character(62) | not null default 'en'::bpchar
 thought    | text          | 

*/
class Thought extends MtDB
	{
	public $tablename = 'thoughts';
	public $fields = array('id', 'approved', 'author_id', 'contributor_id', 'created_at', 'as_rand', 'source_url');  # NOTE: also has 'thought' and 'lang' from thought_translations
	public $limits = array('source_url' => 255);
	public static $langs = array('en', 'es', 'fr', 'ja', 'zh', 'de', 'it', 'pt', 'ru', 'ar');
	public $lang = 'en';
	function __construct($id_or_array, $lang='en')
		{
		$this->lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$this->db = Get::db('musicthoughts');
		if(is_array($id_or_array))
			{
			$this->me = $id_or_array;
			$this->id = intval($id_or_array['id']);
			}
		elseif(is_int($id_or_array))
			{
			$array = Thought::fetch_array($id_or_array, $this->lang);
			if($array !== false)
				{
				$this->id = intval($array['id']);
				$this->me = $array;
				}
			}
		}

	function kill()
		{
		$this->db->query("DELETE FROM categories_thoughts WHERE thought_id={$this->id}");
		$this->db->query("DELETE FROM thought_translations WHERE thought_id={$this->id}");
		$this->db->query("DELETE FROM thoughts WHERE id={$this->id}");
		$this->db->query("SELECT id FROM thoughts WHERE author_id=" . $this->me['author_id']);
		# if author or contributor unused now, delete them.
		if($this->db->num_rows() == 0)
			{
			$this->db->query("DELETE FROM authors WHERE id=" . $this->me['author_id']);
			}
		$this->db->query("SELECT id FROM thoughts WHERE contributor_id=" . $this->me['contributor_id']);
		if($this->db->num_rows() == 0)
			{
			$this->db->query("DELETE FROM contributors WHERE id=" . $this->me['contributor_id']);
			}
		}

	# CUSTOM: adds to two tables
	function add2($array)
		{
		# requires 'thought' and 'lang' in array!
		if(!isset($array['thought']) || mb_strlen($array['thought']) < 5  || !isset($array['lang']) || !in_array($array['lang'], Thought::$langs))
			{
			return false;
			}
		$set = array();
		foreach($this->fields as $key)
			{
			if($key == 'id') { continue; }
			if(isset($array[$key]))
				{
				# maxlength of fields?
				if(isset($this->limits) && isset($this->limits[$key]))
					{
					$array[$key] = mb_substr($array[$key], 0, $this->limits[$key] - 1);
					}
				$set[$key] = trim($array[$key]);
				}
			}
		$thought_id = $this->db->insert('thoughts', $set);
		$set = array('thought_id' => $thought_id, 'thought' => $array['thought'], 'lang' => $array['lang']);
		$this->db->insert('thought_translations', $set);
		return $thought_id;
		}

	function author()
		{
		if(intval($this->me['author_id']) == 0) { return false; }
		return new Author(intval($this->me['author_id']));
		}

	function contributor()
		{
		if(intval($this->me['contributor_id']) == 0) { return false; }
		return new Contributor(intval($this->me['contributor_id']));
		}

	function categories()
		{
		$results = array();
		$this->db->query("SELECT categories.* FROM categories LEFT JOIN categories_thoughts ON categories.id=categories_thoughts.category_id WHERE categories_thoughts.thought_id=" . $this->id);
		while($c = $this->db->next_record())
			{
			$results[$c['id']] = new Category($c);
			}
		return $results;
		}

	# use before add_categories when editing a single thought, to make them sync
	function delete_categories()
		{
		$this->db->query("DELETE FROM categories_thoughts WHERE thought_id=" . $this->id);
		}

	function add_categories($array_of_ids)
		{
		$existing_ids = array();
		foreach($this->categories() as $c)
			{
			$existing_ids[] = $c->id;
			}
		foreach($array_of_ids as $category_id)
			{
			$category_id = intval($category_id);
			if(in_array($category_id, $existing_ids)) { continue; }
			$this->db->query("INSERT INTO categories_thoughts (thought_id, category_id) VALUES ({$this->id}, $category_id)");
			$existing_ids[] = $category_id;
			}
		}

	function intro($howmany_words=10)
		{
		$string = str_replace(array("\n", "\r", "\t"), ' ', $this->me['thought']);
		$string = str_replace('  ', ' ', $string);
		$string = str_replace('  ', ' ', $string);
		$words = $this->words($string);
		$excerpt = '';
		for($i=0; $i < $howmany_words; $i++)
			{
			$excerpt .= array_shift($words) . $this->wordjoiner();
			}
		return trim($excerpt) . '...';
		}

	# really only for back-end admin use
	function translations()
		{
		$res = array();
		$this->db->query("SELECT * FROM thought_translations WHERE thought_id=" . $this->id);
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new ThoughtTranslation($x);
			}
		return $res;
		}

	# send to lang.pro to be translated - only when cleaned!
	function send2langpro()
		{
		$ts = $this->translations();
		if(count($ts) > 1) { return false; }
		$t = array_shift($ts);
		$post = 'project_id=1';  # that's MusicThought's ID# at lang.pro
		$post .= '&remote_id=' . $this->id;
		$post .= '&lang=' . $t->lang();
		$post .= '&original=' . urlencode($t->thought());
		$post .= '&context_url=' . urlencode('http://musicthoughts.com/t/' . $this->id);
		$output = shell_exec('curl -s -i -X POST -d "' . $post . '" -u sivers@gmail.com:8uhb7ygv http://lang.pro/original');
		return true;
		}

	private function words($string)
		{
		switch($this->lang)
			{
			case 'ja': case 'kr': case 'zh':
				$words = array();
				$len = mb_strlen($string);
				for($i=0; $i < $len; $i++)
					{
					$words[] = mb_substr($string, $i, 1);
					}
				return $words;
				break;
			default:
				return explode(' ', $string);
				break;
			}
		}

	private function wordjoiner()
		{
		switch($this->lang)
			{
			case 'ja': case 'kr': case 'zh':
				return '';
				break;
			default:
				return ' ';
				break;
			}
		}

	static function fetch_array($id, $lang)
		{
		$id = intval($id);
		if($id == 0) { return false; }
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$db = Get::db('musicthoughts');
		$q1 = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts"
		. " LEFT JOIN thought_translations ON thoughts.id=thought_translations.thought_id"
		. " WHERE thoughts.id = %d";
		$q2 = " AND thought_translations.lang='%s' LIMIT 1";
		$query = sprintf($q1 . $q2, $id, $lang);
		$db->query($query);
		if($db->num_rows())
			{
			return $db->next_record();
			}
		# fall back to English if not found in $lang
		$db->query(sprintf($q1 . $q2, $id, 'en'));
		if($db->num_rows())
			{
			return $db->next_record();
			}
		# still not found? try with no language restriction
		$db->query(sprintf($q1, $id));
		if($db->num_rows())
			{
			return $db->next_record();
			}
		return false;
		}

	static function fetch($id, $lang)
		{
		$array = Thought::fetch_array($id, $lang);
		if($array === false) { return false; }
		return new Thought($array, $lang);
		}

	static function random1($lang)
		{
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$db = Get::db('musicthoughts');
		$query = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts"
		. " LEFT JOIN thought_translations ON thoughts.id=thought_translations.thought_id"
		. " WHERE as_rand=TRUE"
		. " AND thought_translations.lang='$lang'"
		. " ORDER BY RANDOM() LIMIT 1";
		$db->query($query);
		return new Thought($db->next_record(), $lang);
		}

	static function unapproved1()
		{
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM thoughts WHERE approved=FALSE ORDER BY id ASC LIMIT 1");
		return ($db->num_rows()) ? new Thought($db->next_record()) : false;
		}

	# PRIVATE : use this to return any list of thoughts, once you've made the query
	static function for_query($query, $lang)
		{
		$results = array();
		$db = Get::db('musicthoughts');
		$db->query($query);
		while($t = $db->next_record())
			{
			$results[$t['id']] = new Thought($t, $lang);
			}
		return $results;
		}

# arrays of thoughts:

	static function all($lang, $order='DESC', $limit=false, $offset=false)
		{
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$order = ($order=='ASC') ? 'ASC' : 'DESC';
		$query = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts, thought_translations"
		. " WHERE thoughts.id=thought_translations.thought_id"
		. " AND thought_translations.lang='$lang'"
		. " AND approved=TRUE"
		. " ORDER BY id $order";
		if($limit)
			{
			$query .= " LIMIT " . intval($limit);
			}
		if($offset)
			{
			$query .= " OFFSET " . intval($offset);
			}
		return Thought::for_query($query, $lang);
		}

	static function in_category($id, $lang)
		{
		$id = intval($id);
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$query = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts, categories_thoughts, thought_translations"
		. " WHERE thoughts.id=thought_translations.thought_id"
		. " AND thoughts.id=categories_thoughts.thought_id"
		. " AND thought_translations.lang='$lang'"
		. " AND categories_thoughts.category_id=$id"
		. " AND approved=TRUE"
		. " ORDER BY id DESC";
		return Thought::for_query($query, $lang);
		}

	static function for_author($author_id, $lang)
		{
		$author_id = intval($author_id);
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$query = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts, thought_translations"
		. " WHERE thoughts.id=thought_translations.thought_id"
		. " AND thought_translations.lang='$lang'"
		. " AND author_id=$author_id"
		. " AND approved=TRUE"
		. " ORDER BY id DESC";
		return Thought::for_query($query, $lang);
		}

	static function for_contributor($contributor_id, $lang)
		{
		$contributor_id = intval($contributor_id);
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$query = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts, thought_translations"
		. " WHERE thoughts.id=thought_translations.thought_id"
		. " AND thought_translations.lang='$lang'"
		. " AND contributor_id=$contributor_id"
		. " AND approved=TRUE"
		. " ORDER BY id DESC";
		return Thought::for_query($query, $lang);
		}

	static function newest($limit, $lang)
		{
		return Thought::all($lang, 'DESC', $limit);
		}

	static function search4($q, $lang)
		{
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$query = "SELECT thoughts.*, thought_translations.thought"
		. " FROM thoughts, thought_translations"
		. " WHERE thoughts.id=thought_translations.thought_id"
		. " AND thought_translations.lang='$lang'"
		. " AND approved=TRUE"
		. " AND LOWER(thought) LIKE '%" . pg_escape_string(strtolower($q)) . "%'"
		. " ORDER BY id DESC";
		return Thought::for_query($query, $lang);
		}

	# return array of arrays: category as key, results as array of Thought
	# turn key into constant($key) when displaying in controller!
	static function search($q, $lang)
		{
		$lang = (in_array($lang, Thought::$langs)) ? $lang : 'en';
		$res = array();
		$x = Thought::search4($q, $lang);
		if(count($x))
			{
			$res['MUSICTHOUGHTS'] = $x;
			}
		$a = Author::search($q);
		if($a !== false)
			{
			$x = $a->thoughts($lang);
			if(count($x))
				{
				$res['AUTHOR'] = $x;
				}
			}
		$c = Contributor::search($q);
		if($c !== false)
			{
			$x = $c->thoughts($lang);
			if(count($x))
				{
				$res['CONTRIBUTOR'] = $x;
				}
			}
		return $res;
		}

	# DIFFERENT : ADMIN ONLY : no lang because need to see all unapproved, then assumed each would be shown all its translations separately
	static function unapproved()
		{
		$query = "SELECT * FROM thoughts WHERE approved=FALSE ORDER BY id DESC";
		return Thought::for_query($query, false);
		}

	}
?>
