<?php
/**
 * CloneUI.com - Instagram Clone
 * Users Controller
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 * @license     Affero General Public License
 *
 * @since  	    Thursday, November 29, 2012, 12:52 PM GMT+1
 * @modified    $Date: 2011-11-16 18:27:16 +0100 (Wed, 16 Nov 2011) $ $Author: mknox $
 * @version     $Id: IndexController.php 5139 2011-11-16 17:27:16Z mknox $
 *
 * @category    Controllers
 * @package     Instagram Clone
*/

class UsersController extends Zend_Controller_Action
{
	private $_requestObj;
	private $_requestUri;
	
    public function __call( $method, $args ) {}

    public function init()
    {
        $this->_requestObj	= $this->getRequest();
        $this->_requestUri	= $this->_requestObj->getRequestUri();
        
        $action = $this->getRequest()->getParam('action');                
    }

    public function indexAction() {}

    public function editAction() {}
    
    public function displayprofileAction()
    {
    	$Instagram_Users = new Instagram_Users();
    	$data = $this->getRequest()->getParam('data');
    	    	 
    	if( $Instagram_Users->usernameExists( $data['username'] ) ) {
    		$this->view->data = $data;			
    	} else {
    		throw new Zend_Controller_Action_Exception( 'User does not exist...', 404 );    		
    	}    	
    } 
}