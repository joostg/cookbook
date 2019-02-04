<?php

namespace cookbook\frontend\classes;

abstract class Base
{
    protected $baseUrl;
    protected $capsule;
    protected $ci;
    protected $flash;
    protected $qs;
    protected $paging;
    protected $slugify;
    protected $view;

	public function __construct(\Slim\Container $ci)
    {
        $this->ci = $ci;

        $this->capsule = $this->ci->get('capsule');
        $this->flash = $this->ci->get('flash');
        $this->view = $this->ci->get('view');
        $this->slugify = $this->ci->get('slugify');
        $this->baseUrl = $this->ci->get('settings')->get('base_url_frontend');

        $this->qs = new \cookbook\model\helper\Querystring(null);

        $this->setPaging();
	}

    protected function render($response, array $data, $file = null)
    {
        $file = $this->getTemplateFile($file);
        $class = str_replace('\\', '/', strtolower(get_class($this)));
        $class = str_replace('cookbook/', '', $class);
        $class = str_replace('classes', 'tpl', $class);

        $template = $class . '/' . $file . '.tpl';

        $data['global'] = array(
            'base_url' => $this->baseUrl,
            'return_url' => $this->getReturnUri(),
        );

        $data['flash'] = $this->flash->getMessages();

        $data['menu_items'] = $this->getMenuItems();

        return $this->view->render($response, $template, array('data' => $data));
    }

    protected function getMenuItems()
    {
        $data = array(
            array(
                'link' => '',
                'label' => 'Home',
            ),
            array(
                'link' => '/recepten',
                'label' => 'Recepten',
            ),
        );

        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, "?")) {
            $uri = substr($uri, 0, strpos($uri, "?"));
        }

        foreach ($data as &$menuItem) {
            if ($menuItem['link'] && strpos($uri, $menuItem['link']) === 0) {
                $menuItem['active'] = true;
            }
        }

        return $data;
    }


    protected function _buildLimitStatement()
    {
        $start = ($this->_paging->getCurrentPage() - 1) * $this->_paging->getLimit();
        return 'LIMIT ' . $start . ', ' . $this->_paging->getLimit();
    }

    protected function getReturnUri()
    {
        $uri = $this->baseUrl;
        if (isset($_SESSION['returnUrl'])) {
            $uri .= $_SESSION['returnUrl'];
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

    protected function _getQueryFilter()
    {
        if (!$this->qs->isPresent('q')) {
            return false;
        }

        return $this->qs->getValue('q');
    }

    public function setPaging()
    {
        $this->paging = new \cookbook\model\helper\Paging($this->qs);

        if ($this->qs->isPresent('p')) {
            $this->paging->setCurrentPage($this->qs->getValue('p'));
        }
        if ($this->qs->isPresent('l')) {
            $this->paging->setLimit($this->qs->getValue('l'));
        }
    }
}
