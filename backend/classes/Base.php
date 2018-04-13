<?php
namespace cookbook\backend\classes;
abstract class Base
{
    protected $baseUrl;
    protected $capsule;
    protected $ci;
    protected $flash;
    protected $view;
    protected $slugify;

    public function __construct(\Slim\Container $ci)
    {
        $this->ci = $ci;

        $this->capsule = $this->ci->get('capsule');
        $this->flash = $this->ci->get('flash');
        $this->view = $this->ci->get('view');
        $this->slugify = $this->ci->get('slugify');
        $this->baseUrl = $this->ci->get('settings')->get('base_url');
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
        );

        $data['flash'] = $this->flash->getMessages();

        return $this->view->render($response, $template, array('data' => $data));
    }

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

    protected function getLoggedInUserID()
    {
        return $_SESSION['user']['id'];
    }
}
