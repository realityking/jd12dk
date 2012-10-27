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
		$model = new GModelComments;
		$comments = $model->getAll();

		$commentMarkup = '';
		foreach ($comments as $comment)
		{
			$date = new JDate($comment['date']);
			$commentMarkup .= '<article><h3><a href="mailto:' . htmlspecialchars($comment['Email']) . '">' . htmlspecialchars($comment['Name']) . '</a> wrote:</h3>
			<p>' . htmlspecialchars($comment['Comment']) . '</p>
			<small>' . $date->format('d.m.Y', true) . ', ' . htmlspecialchars(long2ip($comment['Ip'])) . '</small></article>';
		}

		return '
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
		';
	}
}
