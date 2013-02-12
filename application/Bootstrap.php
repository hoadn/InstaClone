<?php
/**
 * CloneUI.com - Instagram Clone
 * Bootstrap
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 * @license     Affero General Public License
 *
 * @since       Thursday, April 21, 2011 / 01:04 AM GMT+1
 * @edited      $Date: 2011-10-21 15:27:34 +0200 (Fri, 21 Oct 2011) $
 * @version     $Id: Bootstrap.php 4693 2011-10-21 13:27:34Z mknox $
 *
 * @category    Bootstrap
 * @package     Instagram Clone
 */

error_reporting( E_ALL );
ini_set( 'display_errors', true );
set_time_limit( 0 );
date_default_timezone_set( 'Europe/Berlin' );

require_once( 'functions.php' );
require_once( 'constants.php' );

ini_set( 'error_log', LOG_DIR.'/php-errors-'.date('m-d-Y').'.log' );

require_once( 'Zend/Loader/Autoloader.php' );
$Zend_Loader_Autoloader = Zend_Loader_Autoloader::getInstance();
$Zend_Loader_Autoloader->setFallbackAutoloader( true );

require_once( 'db.php' );

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    private $_CloneUI_Cache;

    /*
     * Bootstrap constructor
     *
     * @param   string  $env    application environment
     */
    public function __construct( $env )
    {
    	$this->_setupFirePHP();
        $this->_CloneUI_Cache = new CloneUI_Cache;
        $this->_setupSession();
        $this->_setupLocale();
        $this->_setupCache();
        $this->_setupMySQL();
        $this->_setupSiteConfig();
        $this->_setRunEnv();
        $this->_setupLanguage();
        $this->_updateUserSession();
    }

    private function _setupSessionParams()
    {
        // 10 years
        if(!defined('COOKIE_TIMEOUT')) {
            define('COOKIE_TIMEOUT', 315360000);
        }

        if(!defined('GARBAGE_TIMEOUT')) {
            define('GARBAGE_TIMEOUT', COOKIE_TIMEOUT);
        }

        ini_set('session.gc_maxlifetime', GARBAGE_TIMEOUT);
        session_set_cookie_params(COOKIE_TIMEOUT, '/');

        // setting session dir
        if(isset($_SERVER['HTTP_HOST'])) {
            $sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
        } else {
            $sessdir = '/tmp/instagram';
        }

        // if session dir not exists, create directory
        if (!is_dir($sessdir)) {
            @mkdir($sessdir, 0777);
        }

        //if directory exists, then set session.savepath otherwise let it go as is
        if(is_dir($sessdir)) {
            ini_set('session.save_path', $sessdir);
        }
    }

    private function _setupSiteConfig()
    {
        $CloneUI_Instagram = new CloneUI_Instagram;
        $CloneUI_Instagram->defineSiteConfig();
    }

    private function _setupSession()
    {
        $this->_setupSessionParams();

        if( !isset( $_SESSION ) ) {
            session_start();
        }

        setInitialSessionValues();        
    }

    /**
     * setup Zend_Cache
     *
     * @link    http://framework.zend.com/manual/en/zend.cache.introduction.html
     * @todo    move cache lifetime to ini file
     */
    private function _setupCache()
    {
        $this->_CloneUI_Cache->setupCache( 86400, 'cache' );
        $this->_CloneUI_Cache->setupCache( 3600, 'cacheOneHour' );
        $this->_CloneUI_Cache->setupCache( 900, 'cacheFifteenMin' );
    }

    /**
     * setup a MySQL connection
     */
    protected function _setupMySQL()
    {
        $config     = new Zend_Config_Ini( APP_DIR.'/configs/db.ini', 'live' );
        $db         = $config->params->toArray();

        $mysql      = mysql_connect( $db['host'], $db['username'], $db['password'] ) OR die( mysql_error() );
        $mysqldb    = mysql_select_db( $db['dbname'], $mysql ) OR die( mysql_error() );

        // set charset
        mysql_set_charset( 'utf8', $mysql );
    }

    protected function _setupLocale()
    {
        $_SESSION['user']['locale'] = determineUserLocale();
    }

    protected function _setupLanguage()
    {
        require_once( 'Instagram/Languages.php' );
        $Instagram_Languages = new Instagram_Languages();

        $_SESSION['user']['language_id']    = $Instagram_Languages->fetchLanguageIdByLocale( $_SESSION['user']['locale'] );
        $_SESSION['site']['phrases']        = $Instagram_Languages->fetchPhrasesByLanguageId( $_SESSION['user']['language_id'] );
    }

    protected function _setRunEnv()
    {
        $env = determineRunEnvironment();
        setRunEnvironment( $env );

        Zend_Registry::set( 'RUN_ENV', $env );
    }
    
    protected function _updateUserSession()
    {
		// we want to update the user session on every page hit
    	$Instagram_Users = new Instagram_Users();		
    	$Instagram_Users->updateUserSession();		    	
    }
    
    protected function _setupFirePHP()
    {
    	require_once('FirePHPCore/FirePHP.class.php');
    	$firephp = FirePHP::getInstance( true );    	
    	require_once('FirePHPCore/fb.php');
    	
		// disable for non-admin users    	    	
    	$firephp->setEnabled( true );

    	$firephp->registerErrorHandler( $throwErrorExceptions = false );
    	$firephp->registerExceptionHandler();
    	$firephp->registerAssertionHandler(
    			$convertAssertionErrorsToExceptions=true,
    			$throwAssertionExceptions=false);
    	
    	// START:	FirePHP
    	$firebugWriter = new Zend_Log_Writer_Firebug();
    	$firebugLogger = new Zend_Log( $firebugWriter );
    	Zend_Registry::set( 'firebugLogger', $firebugLogger );    	
    	// END:		FirePHP    	
    }

    public function run()
    {    	    	
        $front = Zend_Controller_Front::getInstance();
        $front->throwExceptions( false );
        $front->setControllerDirectory(
                        array('default' => PATH.'/application/modules/public/controllers')
                    );
        $front->setParam( 'useDefaultControllerAlways', false );
        $front->setParam( 'displayExceptions', false );

        try {
            $front->dispatch();
        } catch(Exception $e) {
            $request = $front->getRequest();
            $request->setModuleName('default');
            $request->setControllerName('error');
            $request->setActionName('error');

            $error              = new Zend_Controller_Plugin_ErrorHandler();
            $error->type        = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER;
            $error->request     = clone($request);
            $error->exception   = $e;
            $request->setParam('error_handler', $error);
        }
    }
}