<?php
abstract class Base {
	protected $ci;

	public function __construct(Slim\Container $ci) {
		$this->ci = $ci;
		$this->db = $this->ci->get('db');
		$this->view = $this->ci->get('view');
	}
}
