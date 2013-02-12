<?php
/**
 * Instagram Clone
 * Index
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 *
 * @since       Wednesday, July 06, 2011 / 10:17 AM GMT+1
 * @edited      $Date: 2011-11-03 14:59:43 +0100 (Thu, 03 Nov 2011) $
 * @version     $Id: index.php 4898 2011-11-03 13:59:43Z mknox $
 *
 * @package     Instagram Clone
 */

error_reporting(E_ALL);
ini_set('display_errors', true);

define('PATH', dirname(__FILE__));
set_include_path(   PATH.'/application/'.PATH_SEPARATOR.
                    PATH.'/application/configs'.PATH_SEPARATOR.
                    PATH.'/application/models'.PATH_SEPARATOR.
                    PATH.'/library/'.PATH_SEPARATOR.
                    get_include_path()
                );

require_once('Bootstrap.php');
$Bootstrap = new Bootstrap('');
$Bootstrap->run();