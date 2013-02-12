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
 * @since  	    Tuesday, November 27, 2012, 04:18 PM GMT+1
 * @modified    $Date: 2011-11-16 18:27:16 +0100 (Wed, 16 Nov 2011) $ $Author: mknox $
 * @version     $Id: IndexController.php 5139 2011-11-16 17:27:16Z mknox $
 *
 * @category    Controllers
 * @package     Instagram Clone
*/

class IndexController extends Zend_Controller_Action
{
    public function init() 
    {
    	if( isset( $_SESSION['user']['logged_in'] ) ) {
    		if( $_SESSION['user']['logged_in'] ) {
    			header( 'Location: '.BASEURL.'/accounts/edit' );				    			
    		}    		
    	}    	
    }

    public function indexAction() {}
}