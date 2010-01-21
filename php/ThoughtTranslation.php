<?php
class ThoughtTranslation extends MtDB
	{
	public $tablename = 'thought_translations';
	public $fields = array('id', 'thought_id', 'lang', 'thought');

	static function get_by_id_lang($thought_id, $lang)
		{
		$db = Get::db('musicthoughts');
		$db->query("SELECT * FROM thought_translations WHERE thought_id=" . intval($thought_id) . " AND lang='" . pg_escape_string($lang) . "'");
		return ($db->num_rows() == 0) ? false : new ThoughtTranslation($db->next_record());
		}
	}
?>
