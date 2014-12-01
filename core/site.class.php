<?php 
if(!defined('VALID_SITE')) exit('No direct access! ');

//Singleton single class 
class Site
{
	private function __construct()
	{
		//We don't want random new versions of this guy to be constructed 
	}

	public static $single;
	private $includes;

	public static function single()
	{
		if(!isset(self::$single))
			self::$single = new Site();
		return self::$single; 
	}

	public function name()
	{
		return SITE_NAME;
	}

	//The database connection
	public function db($key = 'default')
	{
		global $db_settings; 
		$settings = $db_settings[$key]; 
		if(!isset($settings) || !is_array($settings))
			return false; 
		if(!isset($this->connection[$key]))
		{
			$this->connection[$key] = new Database($settings['host'], 
												$settings['user'], 
												$settings['pass'], 
												$settings['db']); 
		}

		return $this->connection[$key]; 
	}



	public function view($p = false)
	{
		global $page_title, $headerStuff, $includes; 

		if (!$p) {
			$p = 'index';
		}

		$params = explode('/', $p);
		$p1 = $params[0];
	
		//If you're viewing a normal page
		if ($p1 == 'ajax') {
			$handler = $params[1];
			$this->getPrepageSpecial($handler, 'view/ajax/');
		} else {
			$this->getJavascript($p1);
			$this->getPrepage($p1); 
			if(!$page_title)
				$page_title = ucfirst($p1);
			$includes = $this->includes;
			$this->header();
			$this->getPage($p1);
			$this->footer();
		}
	}

	public function getPage($p)
	{
		$url = DOC_ROOT . 'view/pages/' . mb_strtolower($p) . '.php';
		//echo $url; 
		if(file_exists($url))
		{
			include($url);
		}
		else
		{
			$this->getPage('404');
		}
	}

	public function getJavascript($p)
	{
		$url_part = 'static/js/' . mb_strtolower($p) . '.js';
		// echo $url; 
		if(file_exists(DOC_ROOT . $url_part))
		{
			$this->includes['js'][] = SITE_PATH . $url_part;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getPrepage($p)
	{
		return $this->getPrepageSpecial($p, 'view/pre/');
	}

	public function getPrepageSpecial($p, $base)
	{
		$url = DOC_ROOT . $base . mb_strtolower($p) . '.php';
		//echo $url; 
		if(file_exists($url))
		{
			include($url);
		}
		else
		{
			return false;
		}
	}

	public function header()
	{
		include(DOC_ROOT . 'view/header.php');
	}

	public function footer()
	{
		include(DOC_ROOT . 'view/footer.php');
	}
}
