<?php
/**
 * Constants
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 *
 * @since       Saturday, April 23, 2011 / 02:00 PM GMT+1
 * @version     $Id: constants.php 4725 2011-10-24 14:43:49Z mknox $
 * @edited      $Date: 2011-10-24 16:43:49 +0200 (Mon, 24 Oct 2011) $
 *
 * @package     Instagram Clone
 */

define( 'BASE_DIR', dirname( dirname( dirname(__FILE__) ) ) );
define( 'BASEDIR', BASE_DIR );
define( 'BASE_URL', fetchServerURL() );
define( 'BASEURL', BASE_URL );
define( 'ADMIN_URL', BASEURL.'/admin' );
define( 'IMG_URL', BASEURL.'/img' );
define( 'IMGURL', IMG_URL );
define( 'ROOT_DIR', BASEDIR );
define( 'LOG_DIR', ROOT_DIR.'/data/logs' );
define( 'TMP_DIR', ROOT_DIR.'/data/temp' );
define( 'APP_DIR', ROOT_DIR.'/application' );
define( 'PARTIAL_TEMPLATE_DIR', APP_DIR.'/modules/public/views/templates/ata/partials' );
define( 'SHARED_TEMPLATE_DIR', APP_DIR.'/modules/public/views/templates/shared/scripts' );