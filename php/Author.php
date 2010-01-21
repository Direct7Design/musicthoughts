<?php
class Author extends MtDB
	{
	public $tablename = 'authors';
	public $fields = array('id', 'name', 'url');
	public $limits = array('name' => 127, 'url' => 255);

	function thoughts($lang)
		{
		return Thought::for_author($this->id, $lang);
		}

	static function fetch($id)
		{
		if(intval($id) == 0) { return false; }
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM authors WHERE id=" . intval($id));
		return ($db->num_rows() == 0) ? false : new Author($db->next_record());
		}

	static function fetch_by_name($name)
		{
		if(strlen($name) < 2) { return false; }
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM authors WHERE LOWER(name)='" . pg_escape_string(strtolower(trim($name))) . "'");
		return ($db->num_rows() == 0) ? false : new Author($db->next_record());
		}

	static function all()
		{
		$res = array();
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM authors ORDER BY name ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Author($x);
			}
		return $res;
		}

	static function tops($limit=10)
		{
		$res = array();
		$db = Get::db('musicthoughts');
		$query = "SELECT authors.*, COUNT(*) AS howmany"
		. " FROM thoughts, authors"
		. " WHERE thoughts.author_id=authors.id"
		. " AND name NOT IN ('', 'anonymous', 'unknown')"
		. " GROUP BY authors.id, authors.name, authors.url"
		. " ORDER BY COUNT(*) DESC"
		. " LIMIT " . intval($limit);
		$db->query($query);
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Author($x);
			}
		return $res;
		}

	static function search($q)
		{
		foreach(Author::all() as $a)
			{
			if(mb_stripos($a->name(), $q) !== false)
				{
				return $a;
				}
			}
		return false;
		}

	# returns array of Author objects that now have a howmany() attribute, showing howmany that author has
	static function all_howmany()
		{
		$db = Get::db('musicthoughts');
		$query = "SELECT authors.*, COUNT(thoughts.id) AS howmany"
		. " FROM thoughts"
		. " LEFT JOIN authors ON thoughts.author_id=authors.id"
		. " WHERE thoughts.approved=TRUE"
		. " AND authors.name NOT IN ('unknown', '', 'proverb')"
		. " GROUP BY authors.id, authors.name, authors.url"
		. " ORDER BY COUNT(thoughts.id) DESC";
		$db->query($query);
		$res = array();
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Author($x);
			}
		return $res;
		}
	}
?>
