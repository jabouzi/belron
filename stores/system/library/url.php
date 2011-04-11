<?php if(!defined('DINGO')){die('External Access to File Denied');}

/**
 * Dingo Framework URL Library
 *
 * @Author          Evan Byrne
 * @Copyright       2008 - 2010
 * @Project Page    http://www.dingoframework.com
 * @docs            http://www.dingoframework.com/docs/url-helper
 */

class url
{
	// Base URL
	// ---------------------------------------------------------------------------
	public static function base($ShowIndex = FALSE)
	{
		if($ShowIndex AND !MOD_REWRITE)
		{
			// Include "index.php"
			return(BASE_URL.'index.php/');
		}
		else
		{
			// Don't include "index.php"
			return(BASE_URL);
		}
	}
	
	
	// Page URL
	// ---------------------------------------------------------------------------
	public static function page($path = FALSE)
	{
		if(MOD_REWRITE)
		{
			return(url::base().$path);
		}
		else
		{
			return(url::base(TRUE).$path);
		}
	}
    
    // Root URL
	// ---------------------------------------------------------------------------
	public static function root()
	{
		return($_SERVER['DOCUMENT_ROOT'].ROOT);
	}	
	
	// Redirect
	// ---------------------------------------------------------------------------
	public static function redirect($url = '')
	{
		header('Location: '.url::base(TRUE).$url);
		exit;
	}
    
    public static function get_redirect_url()
    {
        $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
        $host     = $_SERVER['HTTP_HOST'];
        $params   = $_SERVER['REQUEST_URI'];
        $currentUrl = $protocol . '://' . $host . $params;    
        $redirect_url = explode(url::base(),$currentUrl);
        
        return $redirect_url;
    }
    
    public static function change_language()
    {
        $language = 'en_CA';
        if (session::get('lang') == 'fr') $language = 'fr_CA';       
        
        putenv("LANG=$language"); 
        setlocale(LC_ALL, $language);
        
        $domain = 'messages';
        bindtextdomain($domain, url::root()."application/language/locale");
        bind_textdomain_codeset($domain, 'UTF-8');
        textdomain($domain);
    }
    
    public static function get_home()
    {
        $home_url = url::page('storesmanager/index');
                
        return $home_url;
    } 
}
