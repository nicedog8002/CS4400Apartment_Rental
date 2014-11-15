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
		global $page_title, $headerStuff; 

		if (!$p) {
			$p = 'index';
		}

		$params = explode('/', $p);
		$p1 = $params[0];
	
		//If you're viewing a normal page

		$this->getPrepage($p1); 
		if(!$page_title)
			$page_title = ucfirst($p1);

		$this->header();
		$this->getPage($p1);
		$this->footer();
		

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

	public function getPrepage($p)
	{
		$url = DOC_ROOT . 'view/pre/' . mb_strtolower($p) . '.php';
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
