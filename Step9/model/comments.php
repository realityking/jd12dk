<?php

class GModelComments extends JModelDatabase
{
	public function getAll()
	{
		$db = $this->getDb();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('Comments');
		$db->setQuery($query);

		return $db->loadAssocList();
	}

	public function add($name, $email, $comment, $ip, JDate $date)
	{
		$db = JFactory::getDbo();
		$query = 'INSERT INTO Comments (Name, Email, Comment, Ip, date)
					VALUES (' . $db->quote($name) . ', ' . $db->quote($email) . ', ' . $db->quote($comment) . ', ' . $db->quote(ip2long($ip)) . ', ' . $db->quote($date->toISO8601()) . ');';
		$db->setQuery($query);
		return $db->execute();
	}
}
