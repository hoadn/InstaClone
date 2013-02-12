<?php
/**
 * CloneUI.com - Instagram Clone
 * Core Library
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 * @license     Affero General Public License
 *
 * @since  	    Tuesday, November 27, 2012, 04:39 PM GMT+1
 * @modified    $Date: 2011-11-16 18:27:16 +0100 (Wed, 16 Nov 2011) $ $Author: mknox $
 * @version     $Id: IndexController.php 5139 2011-11-16 17:27:16Z mknox $
 *
 * @category    Core Library
 * @package     Instagram Clone
 */

class CloneUI_Instagram
{
    /**
     * fetch site configuration from the DB
     *
     * @return  array
     */
    public function fetchSiteConfig()
    {
        $data   = array();

        $sql    = "SELECT * FROM `".DB_TABLE_PREFIX."site_config` ";
        $res    = mysql_query( $sql ) OR die( mysql_error() );

        if( mysql_num_rows( $res ) > 0 ) {
            while( $row = mysql_fetch_assoc( $res ) ) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /**
     * define site configuration
     */
    public function defineSiteConfig()
    {
        $config = $this->fetchSiteConfig();

        if( !empty( $config ) ) {
            foreach( $config AS $key => $value ) {
                define( strtoupper( $value['name'] ), $value['value'] );
            }
        }
    }
}