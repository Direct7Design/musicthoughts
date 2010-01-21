<?php
class Contributor extends MtDB
	{
	public $tablename = 'contributors';
	public $fields = array('id', 'shared_id', 'name', 'email', 'url', 'place');
	public $limits = array('name' => 127, 'email' => 127, 'url' => 255, 'place' => 255);

	function thoughts($lang)
		{
		return Thought::for_contributor($this->id, $lang);
		}

	# uses the 'people' postgresql database from sivers.org
	function person()
		{
		return Person::fetch($this->me['shared_id']);
		}

	static function fetch($id)
		{
		if(intval($id) == 0) { return false; }
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM contributors WHERE id=" . intval($id));
		return ($db->num_rows() == 0) ? false : new Contributor($db->next_record());
		}


	static function fetch_by_email($email)
		{
		$email = trim(strtolower($email));
		if(!is_valid_email_address($email)) { return false; }
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM contributors WHERE LOWER(email)='" . pg_escape_string($email) . "' LIMIT 1");
		return ($db->num_rows() == 0) ? false : new Contributor($db->next_record());
		}

	static function all()
		{
		$res = array();
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM contributors ORDER BY id DESC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Contributor($x);
			}
		return $res;
		}

	static function tops($limit=10)
		{
		$res = array();
		$db = Get::db('musicthoughts');
		$query = "SELECT contributors.*, COUNT(*) AS howmany"
		. " FROM thoughts, contributors"
		. " WHERE thoughts.contributor_id=contributors.id"
		. " AND name NOT IN ('', 'anonymous', 'unknown')"
		. " GROUP BY contributors.id, contributors.shared_id, contributors.name, contributors.email, contributors.url, contributors.place"
		. " ORDER BY COUNT(*) DESC"
		. " LIMIT " . intval($limit);
		$db->query($query);
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Contributor($x);
			}
		return $res;
		}

	static function search($q)
		{
		foreach(Contributor::all() as $c)
			{
			if(mb_stripos($c->name(), $q) !== false)
				{
				return $c;
				}
			}
		return false;
		}

	# returns array of Contributor objects that now have a howmany() attribute, showing howmany that contributor has
	static function all_howmany()
		{
		$db = Get::db('musicthoughts');
		$query = "SELECT contributors.*, COUNT(thoughts.id) AS howmany"
		. " FROM thoughts"
		. " LEFT JOIN contributors ON thoughts.contributor_id=contributors.id"
		. " WHERE thoughts.approved=TRUE"
		. " AND contributors.name NOT IN ('unknown', '', 'proverb')"
		. " GROUP BY contributors.id, contributors.shared_id, contributors.name, contributors.email, contributors.url, contributors.place"
		. " ORDER BY COUNT(thoughts.id) DESC";
		$db->query($query);
		$res = array();
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Contributor($x);
			}
		return $res;
		}
	}
?>
