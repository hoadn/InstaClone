<?php
/**
 * CloneUI.com - Instagram Clone
 * Error Controller
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 * @license     Affero General Public License
 *
 * @since  	    Tuesday, November 27, 2012, 05:35 PM GMT+1
 *
 * @since       Saturday, April 23, 2011 / 07:23 AM GMT+1
 * @version     $Id: ErrorController.php 4896 2011-11-03 10:45:55Z mknox $
 * @edited      $Date: 2011-11-03 11:45:55 +0100 (Thu, 03 Nov 2011) $
 *
 * @package     Instagram Clone
 */

class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $errors     = $this->_getParam('error_handler');
        $exception  = $errors->exception;
        $message    = $exception->getMessage();
        $trace      = $exception->getTraceAsString();
        
        $controller	= $this->getRequest()->getParam('controller');
        
        if( strlen( $controller ) ) {
        	$Instagram_Users = new Instagram_Users();
        	
        	if( $Instagram_Users->usernameExists( $controller ) ) {
        		$data = $Instagram_Users->fetchUserDetailsByUsername( $controller );
        		$this->_forward( 'displayprofile', 'users', null, array( 'data' => $data ) );
        		$errors->type = null;	
        	}	
        }

        switch ( $errors->type ) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                // ... get some output to display...
                break;
                
            default:
                // application error; display error page, but don't change
                // status code
                // Log the exception:
                $log = new Zend_Log(
                    new Zend_Log_Writer_Stream(
                        LOG_DIR.'/php-exceptions-'.date('m-d-Y').'.log'
                    )
                );
                $log->debug($exception->getMessage() . "\n" .
                            $exception->getTraceAsString());
                break;
        }

        // clear previous content
        $this->getResponse()->clearBody();

        if( @$_SESSION['user']['is_admin'] ) {
            $this->view->message    = $message;
            $this->view->trace      = $trace;
        } else {
            $this->view->message    = 'Sorry, this page could not be found.';
            $this->view->trace      = null;
        }
    }
}