<?php

defined('_JEXEC') or die;

class GControllerAdd extends JControllerBase
{
	/**
	 * Execute the controller.
	 *
	 * @return  string  The rendered view.
	 */
	public function execute()
	{
		$app = JFactory::getApplication();

		$name    = $this->input->post->getString('name');
		$email   = $this->input->post->getString('email');
		$comment = $this->input->post->getString('comment');

		if (!empty($name) && !empty($email) && !empty($comment))
		{
			$ip = $this->input->server->getString('REMOTE_ADDR');
			$date = new JDate;
			$db = JFactory::getDbo();
			$query = 'INSERT INTO Comments (Name, Email, Comment, Ip, date)
						VALUES (' . $db->quote($name) . ', ' . $db->quote($email) . ', ' . $db->quote($comment) . ', ' . $db->quote(ip2long($ip)) . ', ' . $db->quote($date->toISO8601()) . ');';
			$db->setQuery($query);
			$db->execute();
		}

		$app->redirect('index.php');
	}
}
