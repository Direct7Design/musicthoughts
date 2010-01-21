<?php
class Category extends MtDB
	{
	public $tablename = 'categories';
	public $fields = array('id', 'description');

	function name()
		{
		return constant('MTCATEGORY' . $this->me['id']);
		}

	function thoughts($lang)
		{
		return Thought::in_category($this->me['id'], $lang);
		}

	static function fetch($id)
		{
		if(intval($id) == 0) { return false; }
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM categories WHERE id=" . intval($id));
		return ($db->num_rows() == 0) ? false : new Category($db->next_record());
		}

	static function all()
		{
		$res = array();
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM categories ORDER BY description ASC");
		while($c = $db->next_record())
			{
			$res[$c['id']] = new Category($c);
			}
		return $res;
		}

	static function search($q)
		{
		foreach(Category::all() as $c)
			{
			if(mb_stripos($c->name(), $q) !== false)
				{
				return $c;
				}
			}
		return false;
		}

	# returns array of Category objects that now have a howmany() attribute, showing howmany in that cat
	static function all_howmany()
		{
		$db = Get::db('musicthoughts');
		$query = "SELECT categories.*, COUNT(*) AS howmany"
		. " FROM categories"
		. " LEFT JOIN categories_thoughts ON categories.id=categories_thoughts.category_id"
		. " LEFT JOIN thoughts ON categories_thoughts.thought_id=thoughts.id"
		. " WHERE thoughts.approved=TRUE"
		. " GROUP BY categories.id, categories.description"
		. " ORDER BY description ASC";
		$db->query($query);
		$results = array();
		while($c = $db->next_record())
			{
			$results[$c['id']] = new Category($c);
			}
		return $results;
		}
	}
?>
