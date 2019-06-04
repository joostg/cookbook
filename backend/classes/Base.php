<?php

namespace cookbook\backend\classes;

abstract class Base
{
    /**
     * @var string The full URL of the current environment
     */
    protected $baseUrl;

    /**
     * @var Illuminate\Database\Capsule\Manager The Eloquent database object
     */
    protected $capsule;

    /**
     * @var \Slim\Container The complete dependency container
     */
    protected $ci;

    /**
     * @var \Slim\Flash\Messages For displaying flash messages
     */
    protected $flash;

    /**
     * @var \cookbook\model\helper\Querystring Object for processing the query string
     */
    protected $qs;

    /**
     * @var \cookbook\model\helper\Paging Object for processing paging input
     */
    protected $paging;

    /**
     * @var Cocur\Slugify\Slugify Object for slugifying path names
     */
    protected $slugify;

    /**
     * @var mixed The Twig template engine
     */
    protected $view;

    public function __construct(\Slim\Container $ci)
    {
        $this->ci = $ci;

        $this->capsule = $this->ci->get('capsule');
        $this->flash = $this->ci->get('flash');
        $this->view = $this->ci->get('view');
        $this->slugify = $this->ci->get('slugify');
        $this->baseUrl = $this->ci->get('settings')->get('base_url');

        $this->qs = new \cookbook\model\helper\Querystring();

        $this->setPaging();
    }

    /**
     * Set paging data based on the query string
     */
    public function setPaging()
    {
        $this->paging = new \cookbook\model\helper\Paging($this->qs);

        // current page
        if ($this->qs->isPresent('p')) {
            $this->paging->setCurrentPage($this->qs->getValue('p'));
        }
        // limit items per page
        if ($this->qs->isPresent('l')) {
            $this->paging->setLimit($this->qs->getValue('l'));
        }
    }

    /**
     * @param $response
     * @param array $data
     * @param null $file
     * @return mixed
     */
    protected function render($response, array $data, $file = null)
    {
        $file = $this->getTemplateFile($file);
        $class = str_replace('\\', '/', strtolower(get_class($this)));
        $class = str_replace('cookbook/', '', $class);
        $class = str_replace('classes', 'tpl', $class);

        $template = $class . '/' . $file . '.tpl';

        $data['global'] = array(
            'base_url' => $this->baseUrl,
        );

        $data['flash'] = $this->flash->getMessages();

        $data['menu_items'] = $this->getMenuItems();

        return $this->view->render($response, $template, array('data' => $data));
    }

    /**
     * TODO: store pages in db instead
     * @return array
     */
    protected function getMenuItems()
    {
        $data = array(
            array(
                'link' => '/',
                'label' => 'Home',
            ),
            array(
                'link' => '/recepten',
                'label' => 'Recepten',
            ),
            array(
                'link' => '/ingredienten',
                'label' => 'Ingrediënten',
            ),
            array(
                'link' => '/hoeveelheden',
                'label' => 'Hoeveelheden',
            ),
            array(
                'link' => '/afbeeldingen',
                'label' => 'Afbeeldingen',
            ),
        );

        // determine which page is active
        $uri = str_replace('/achterkant', '', $_SERVER['REQUEST_URI']);

        if (strpos($uri, "?")) {
            $uri = substr($uri, 0, strpos($uri, "?"));
        }

        foreach ($data as &$menuItem) {
            if ($uri == $menuItem['link']) {
                $menuItem['active'] = true;
            }
        }

        return $data;
    }

    /**
     * Basic method to retrieve list of items from the specified eloquent model.
     * @param $model
     * @return mixed
     */
    protected function getItems($model)
    {
        // get filters from query string
        $queryData = $this->qs->getQueryData();

        $model = $model->orderBy($queryData['sort'], $queryData['order']);

        if (isset($queryData['query'])) {
            // TODO: use $model = $model->setQuery($queryData['query']);
            $model = $model->where('name', $queryData['query']);
        }
        if (isset($queryData['filters']) && !empty($queryData['filters'])) {
            foreach ($queryData['filters'] as $key => $value) {
                $model = $model->where($key, $value);
            }
        }

        // Create limits based on current page and items per page
        $this->paging->setNumResults($model->count());

        $model = $model->offset(($this->paging->getCurrentPage() - 1) * $this->paging->getLimit());
        $model = $model->limit($this->paging->getLimit());

        return $model->get();
    }

    /**
     * Creates return url based on data stored in session
     * @return string
     */
    protected function getReturnUri()
    {
        $uri = $this->baseUrl;
        if (isset($_SESSION['returnUrl'])) {
            $returnUrl = str_replace('/achterkant','',$_SESSION['returnUrl']);

            $uri .= $returnUrl;
        }

        return $uri;
    }

	/**
	 * Determines template file to use from calling method name if not explicitly given
	 *
	 * @param null $file the template file name to use, if different from calling method name
	 * @return string the template file name to use
	 */
	protected function getTemplateFile($file = null)
	{
		if ($file !== null) {
			return $file;
		}

		$trace = debug_backtrace(false, 3);
		array_shift($trace); // removes this method call
		array_shift($trace); // removes render() method call
		$caller = array_shift($trace); // gets calling method name
		$file = $caller['function'];

		return $file;
	}

    /**
     * Generate a cryptographic strong random string. Used to generate safe identifiers and validators, for example for
     * cookies.
     * @param $length
     * @param string $keyspace
     * @return string
     */
    protected function random_string($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[$this->crypto_rand_secure(0, $max)];
        }
        return $str;
    }

    /**
     * safe substitution for rand()
     * @param $min
     * @param $max
     * @return mixed
     */
    protected function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range == 0) {
            return $min; // not so random...
        }
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes, $s)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    /**
     * @return mixed The database id of the logged in user
     */
    protected function getLoggedInUserID()
    {
        return $_SESSION['user']['id'];
    }

    public function createSortLink($label, $field, $defaultOrder = 'desc')
    {
        $active = $activeDirection = false;
        $qs = clone $this->qs;

        if ($qs->getValue('s') == $field) {
            $active = true;

            if ($qs->getValue('o') == 'desc') {
                $activeDirection = 'desc';
                $qs->set('o', 'asc');
            } else {
                $activeDirection = 'asc';
                $qs->set('o', 'desc');
            }
        }
        $qs->set('s', $field);

        if (!$qs->isPresent('o')) {
            $qs->set('o', $defaultOrder);
        }

        $link = array(
            'label' => $label,
            'field' => $field,
            'qs' => '?' . $qs->output(),
            'active' => $active,
            'activeDirection' => $activeDirection,
        );

        return $link;
    }

    /**
     * Returns user query input
     * @return bool|string
     */
    protected function _getQueryFilter()
    {
        if (!$this->qs->isPresent('q')) {
            return false;
        }

        return $this->qs->getValue('q');
    }
}
