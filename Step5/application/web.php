<?php
defined('_JEXEC') or die;

class GApplicationWeb extends JApplicationWeb
{
	/**
	 * Class constructor.
	 *
	 * @param   mixed  $input   An optional argument to provide dependency injection for the application's
	 *                          input object.  If the argument is a JInput object that object will become
	 *                          the application's input object, otherwise a default input object is created.
	 * @param   mixed  $config  An optional argument to provide dependency injection for the application's
	 *                          config object.  If the argument is a JRegistry object that object will become
	 *                          the application's config object, otherwise a default config object is created.
	 * @param   mixed  $client  An optional argument to provide dependency injection for the application's
	 *                          client object.  If the argument is a JApplicationWebClient object that object will become
	 *                          the application's client object, otherwise a default client object is created.
	 */
	public function __construct(JInput $input = null, JRegistry $config = null, JApplicationWebClient $client = null)
	{
		parent::__construct($input, $config, $client);

		$this->config->set('session', false);

		// Inject the application into JFactory
		JFactory::$application = $this;

		$this->setUpDB();
	}

	/**
	 * Method to run the Web application routines.
	 *
	 * @return  void
	 */
	protected function doExecute()
	{
		$task = $this->input->post->getCmd('task', 'display');

		if ($task === 'add')
		{
			$this->add();
		}
		elseif ($task === 'display')
		{
			$this->display();
		}
	}

	private function setUpDb()
	{
		$dbo = JDatabaseDriver::getInstance(array('driver' => 'sqlite', 'database' => '/Users/rouven/Sites/jd12dk/guestbook.sqlite'));
		$dbo->setQuery('CREATE TABLE IF NOT EXISTS Comments (Id INTEGER PRIMARY KEY, Name TEXT, Email TEXT, Comment Text, Ip INTEGER, date TEXT)');
		$dbo->execute();

		// Inject database into JFactory
		JFactory::$database = $dbo;
	}

	private function add()
	{
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

		$this->redirect('index.php');
	}

	private function display()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('Comments');
		$db->setQuery($query);
		$comments = $db->loadAssocList();

		$commentMarkup = '';
		foreach ($comments as $comment)
		{
			$date = new JDate($comment['date']);
			$commentMarkup .= '<article><h3><a href="mailto:' . htmlspecialchars($comment['Email']) . '">' . htmlspecialchars($comment['Name']) . '</a> wrote:</h3>
			<p>' . htmlspecialchars($comment['Comment']) . '</p>
			<small>' . $date->format('d.m.Y', true) . ', ' . htmlspecialchars(long2ip($comment['Ip'])) . '</small></article>';
		}

		$this->setBody('
			<!DOCTYPE html>
			<html>
				<head>
					<meta charset="UTF-8" />
					<title>Joomla! Guestbok!</title>
					<link rel="stylesheet" href="../bootstrap.css" />
				</head>
				<body>
					<h1>Guestbook</h1>
					<form action="index.php" enctype="application/x-www-form-urlencoded" method="post">
						<fieldset>
							<input id="name" autofocus="autofocus" type="text" name="name" placeholder="Name" size="40" required="required" /><br />
							<input id="email" type="email" name="email" placeholder="E-Mail" size="40" required="required" /><br />
							<textarea id="comment" name="comment" placeholder="Your Comment" rows="10" cols="40" required="required"></textarea><br />
							<input type="hidden" name="task" value="add" />
							<input type="submit" vale="Send" />
						</fieldset>
					</form>
					<section>
						<h2>Comments</h2>
						' . $commentMarkup . '
					</section>
				</body>
			</html>
		');
	}
}
