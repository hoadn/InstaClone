<?php
/**
 * Instagram Clone
 * Database Connection
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 *
 * @since  	    Thursday, June 16, 2011, 06:40 PM GMT+1
 * @modified    $Date: 2011-07-26 18:50:22 +0200 (Tue, 26 Jul 2011) $ $Author: mknox $
 * @version     $Id: db.php 3614 2011-07-26 16:50:22Z mknox $
 *
 * @category     Database Connection
 * @package      Instagram Clone
*/

$config     = new Zend_Config_Ini(dirname(__FILE__).'/db.ini', 'live');
$options    = $config->params->toArray();

try {
    $db = Zend_Db::factory( $config->adapter, $options );
    Zend_Db_Table_Abstract::setDefaultAdapter( $db );
    Zend_Registry::set( 'dbAdapter', $db );
    define( 'DB_TABLE_PREFIX', $options['table_prefix'] );
} catch ( Exception $e ) {
    exit( $e->getMessage() );
}