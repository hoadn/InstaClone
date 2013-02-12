<?php
/**
 * CloneUI.com - Instagram Clone
 * Accounts Controller
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 * @license     Affero General Public License
 *
 * @since  	    Tuesday, November 27, 2012, 05:55 PM GMT+1
 * @modified    $Date: 2011-11-16 18:27:16 +0100 (Wed, 16 Nov 2011) $ $Author: mknox $
 * @version     $Id: IndexController.php 5139 2011-11-16 17:27:16Z mknox $
 *
 * @category    Controllers
 * @package     Instagram Clone
*/

class AccountsController extends Zend_Controller_Action
{
	private $_requestObj;
	private $_requestUri;
	private	$_firebugLogger;
	
    public function __call( $method, $args ) {}

    public function init()
    {    	    	
    	$this->_firebugLogger	= Zend_Registry::get( 'firebugLogger' );
        $this->_requestObj		= $this->getRequest();
        $this->_requestUri		= $this->_requestObj->getRequestUri();
        
        $action = $this->getRequest()->getParam('action');

        if( !@$_SESSION['user']['logged_in'] ) {
        	switch ( $action ) {
        		case 'login':
        			$this->_forward( 'login', null, null, $_POST );
        			break;
        			
        		default:        			
        			header( 'Location: '.BASEURL.'/login?next='.BASEURL.$this->_requestUri.'' );
        	}        	        	
        }       
    }

    public function indexAction() {}

    public function editAction() {}
    
    public function loginAction()
    {
    	$this->_forward( 'login', 'login', null, $_POST );    	
    }
    
    public function logoutAction()
    {
    	session_unset();
    	session_destroy();
    	    	
    	header( 'Location: '.BASEURL.'' );    	
    }    
}