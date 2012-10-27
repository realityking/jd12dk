<?php

defined('_JEXEC') or die;

class GControllerDisplay extends JControllerBase
{
	/**
	 * Execute the controller.
	 *
	 * @return  string  The rendered view.
	 */
	public function execute()
	{
		$app = $this->getApplication();
		$doc = $app->getDocument();

		$vName = $app->input->getWord('view', 'comments');
		$vFormat = $doc->getType();
		$lName = $app->input->getWord('layout', 'default');

		if (strcmp($vName, 'comments') == 0)
		{
			$app->input->set('view', 'comments');
		}

		// Register the layout paths
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_BASE . '/view/' . $vName . '/tmpl', 'normal');

		$vClass = 'GView' . ucfirst($vName) . ucfirst($vFormat);
		$view = new $vClass(new GModelComments, $paths);
		$view->setLayout($lName);

		// Render our view and return it to the application.
		return $view->render();
	}
}
