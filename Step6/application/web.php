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
		// Execute the task.
		try
		{
			$controller = $this->fetchController($this->input->getCmd('task', 'display'));
			$contents = $controller->execute();
		}
		catch (RuntimeException $e)
		{
			echo $e->getMessage();
			$this->close($e->getCode());
		}

		$this->setBody($contents);
	}

	private function setUpDb()
	{
		$dbo = JDatabaseDriver::getInstance(array('driver' => 'sqlite', 'database' => '/Users/rouven/Sites/jd12dk/guestbook.sqlite'));
		$dbo->setQuery('CREATE TABLE IF NOT EXISTS Comments (Id INTEGER PRIMARY KEY, Name TEXT, Email TEXT, Comment Text, Ip INTEGER, date TEXT)');
		$dbo->execute();

		// Inject database into JFactory
		JFactory::$database = $dbo;
	}

	/**
	 * Method to get a controller object.
	 *
	 * @param   string  $task  The task being executed
	 *
	 * @return  JController
	 *
	 * @throws  RuntimeException
	 */
	protected function fetchController($task)
	{
		if (is_null($task))
		{
			$task = 'default';
		}

		// Set the controller class name based on the task
		$class = 'GController' . ucfirst($task);

		// If the requested controller exists let's use it.
		if (class_exists($class))
		{
			return new $class;
		}

		// Nothing found. Panic.
		throw new RuntimeException('Class ' . $class . ' not found');
	}
}
