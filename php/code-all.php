<?php
require 'init.php';
/*
foreach(Thought::all() as $t)
	{
	$t->set(array('code' => Thought::newcode()));
	}
*/

$db = Get::db('musicthoughts');
$db->query("SELECT id, code FROM thoughts WHERE code IS NOT NULL");
foreach($db->hash_with_key('id') as $thought_id => $code)
	{
	$db->query("UPDATE categories_thoughts SET thought_code='$code' WHERE thought_id=$thought_id");
	}
?>
