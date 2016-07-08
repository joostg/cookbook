<?php

class Recipe extends Base
{
	public function view($request, $response, $args)
	{
		$data = array('data' => 'sa');

		return $this->view->render($response, 'recipe/browse.tpl', $data);
	}
}
