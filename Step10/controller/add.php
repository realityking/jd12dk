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

			$model = new GModelComments;
			$model->add($name, $email, $comment, $ip, new JDate );
		}

		$app->redirect('index.php');
	}
}
