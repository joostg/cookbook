<?php
namespace cookbook\frontend\classes;
abstract class Base {
	protected $ci;
	protected $capsule;
	protected $view;
	protected $slugify;

	public function __construct(\Slim\Container $ci) {
		$this->ci = $ci;
		$this->capsule = $this->ci->get('capsule');
		$this->view = $this->ci->get('view');
		$this->slugify = $this->ci->get('slugify');
	}
}
