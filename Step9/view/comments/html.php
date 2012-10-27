<?php

defined('_JEXEC') or die;

class GViewCommentsHtml extends JViewHtml
{
	public $comments = array();

	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 * @throws  RuntimeException
	 */
	public function render()
	{
		$comments = $this->model->getAll();
		foreach ($comments as $key => $comment)
		{
			$date = new JDate($comment['date']);
			$comments[$key]['date'] = $date->format('d.m.Y', true);
		}

		$this->comments = $comments;

		return parent::render();
	}
}
