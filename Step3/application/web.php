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
	}

	/**
	 * Method to run the Web application routines.
	 *
	 * @return  void
	 */
	protected function doExecute()
	{
		$this->setUpDB();

		$name    = $this->input->post->getString('name');
		$email   = $this->input->post->getString('email');
		$comment = $this->input->post->getString('comment');

		if (!empty($name) && !empty($email) && !empty($comment))
		{
			$db = JFactory::getDbo();
			$query = 'INSERT INTO Comments (Name, Email, Comment)
						VALUES (' . $db->quote($name) . ', ' . $db->quote($email) . ', ' . $db->quote($comment) . ');';
			$db->setQuery($query);

			$db->execute();
		}

		$this->display();
	}

	private function setUpDb()
	{
		$dbo = JDatabaseDriver::getInstance(array('driver' => 'sqlite', 'database' => '/Users/rouven/Sites/jd12dk/guestbook.sqlite'));
		$dbo->setQuery('CREATE TABLE IF NOT EXISTS Comments (Id INTEGER PRIMARY KEY, Name TEXT, Email TEXT, Comment Text, Ip INTEGER, date TEXT)');
		$dbo->execute();

		// Inject database into JFactory
		JFactory::$database = $dbo;
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
			$commentMarkup .= '<article><h3>' . $comment['Name'] . ' wrote:</h3>
			<p>' . $comment['Comment'] . '</p></article>';
		}

		$this->setBody('
			<!DOCTYPE html>
			<html>
				<head>
					<title>Joomla! Guestbok!</title>
				</head>
				<body>
					<h1>Guestbook</h1>
					<form action="index.php" enctype="application/x-www-form-urlencoded" method="post">
						<fieldset>
							<input id="name" autofocus="autofocus" type="text" name="name" placeholder="Name" size="40" required="required" /><br />
							<input id="email" type="email" name="email" placeholder="E-Mail" size="40" required="required" /><br />
							<textarea id="comment" name="comment" placeholder="Your Comment" rows="15" cols="40" required="required"></textarea><br />
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
