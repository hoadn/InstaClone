<?php
/**
 * CloneUI.com - Instagram Clone
 * Instagram User Model
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 * @license     Affero General Public License
 *
 * @since       Thursday, November 29, 2012, 09:51 AM GMT+1 mknox
 * @edited      $Date: 2011-09-02 20:15:11 +0200 (Fri, 02 Sep 2011) $ $Author: mknox $
 * @version     $Id: Account.php 4115 2011-09-02 18:15:11Z mknox $
 */

class Instagram_Users
{
    /**
     * User Login
     *
     * @param   string  $username
     * @param	string	$password
     * @return  string
    */
    public function login( $username, $password )
    {
    	if( !$this->usernameExists( $username ) ) {
    		return 'LOGIN_USERNAME_DOES_NOT_EXIST';	
    	} 
    		    	
        $sql    = "SELECT * FROM `".DB_TABLE_PREFIX."users` ";
        $sql   .= "WHERE `username` = '".mysql_real_escape_string( $username )."' ";
        $sql   .= "AND `password` = '".mysql_real_escape_string( $password )."' ";
        $sql   .= "OR `email` = '".mysql_real_escape_string( $username )."' ";
        $sql   .= "AND `password` = '".mysql_real_escape_string( $password )."' ";
        $sql   .= "LIMIT 1 ";

        $res    = mysql_query( $sql ) OR die( mysql_error() );

        if( mysql_num_rows( $res ) > 0 ) {
            $data = mysql_fetch_assoc( $res );
            
            $_SESSION['user'] = array();
            
            foreach( $data AS $key => $value )  {
            	$_SESSION['user'][$key] = $value;	
            }
            
            $_SESSION['user']['logged_in']		= true;
            $_SESSION['user']['avatar_url'] 	= ( strlen( $_SESSION['user']['avatar_url'] ) ) ? $_SESSION['user']['avatar_url'] : SITE_DEFAULT_AVATAR_URL;
            $_SESSION['user']['profile_url'] 	= BASEURL.'/'.$_SESSION['user']['username'];
            $_SESSION['user']['full_name'] 		= $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name'];
            
            $_SESSION['SITE_DEBUG'] 			= true;
            
            // remove the user's password from the session
            unset( $_SESSION['user']['password'] );
            
            return 'LOGIN_OK';
        } else {
       		return 'LOGIN_INVALID_PASSWORD'; 	
        }      
    }
    
    /**
     * Fetch User Data via User ID
     *
     * @param   string  $userId
     * @return  array
     */
    public function fetchUserDetailsById( $userId )
    {
    	$sql    = "SELECT * FROM `".DB_TABLE_PREFIX."users` ";
    	$sql   .= "WHERE `id` = '".mysql_real_escape_string( $userId )."' ";
    	$sql   .= "LIMIT 1 ";
    
    	$res    = mysql_query( $sql ) OR die( mysql_error() );
    
    	if( mysql_num_rows( $res ) > 0 ) {
    		$data = mysql_fetch_assoc( $res );
    		return $data;
    	} else {
    		return array();
    	}
    }    
    
    /**
     * Fetch User Data via Username
     *
     * @param   string  $username
     * @return  array
     */
    public function fetchUserDetailsByUsername( $username )
    {    		
    	$sql    = "SELECT * FROM `".DB_TABLE_PREFIX."users` ";
    	$sql   .= "WHERE `username` = '".mysql_real_escape_string( $username )."' ";
    	$sql   .= "OR `email` = '".mysql_real_escape_string( $username )."' ";
    	$sql   .= "LIMIT 1 ";
    
    	$res    = mysql_query( $sql ) OR die( mysql_error() );
    
    	if( mysql_num_rows( $res ) > 0 ) {
    		$data = mysql_fetch_assoc( $res );
    		return $data;
    	} else {
    		return array();	
    	}
    }    

    /**
     * Determine if a username exists or not
     *
     * @param   string	$username
     * @return  boolean	
    */
    public function usernameExists( $username )
    {
        $sql    = "SELECT * FROM `".DB_TABLE_PREFIX."users` ";
        $sql   .= "WHERE `username` = '".mysql_real_escape_string( $username )."' ";
        $sql   .= "OR `email` = '".mysql_real_escape_string( $username )."' ";
        $sql   .= "LIMIT 1 ";

        $res    = mysql_query( $sql ) OR die( mysql_error() );

        if( mysql_num_rows( $res ) > 0 ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Update a user's session
     */    
    public function updateUserSession()
    {
		if( isset( $_SESSION['user']['id'] ) ) {
			$data = $this->fetchUserDetailsById( $_SESSION['user']['id'] );
			            
            foreach( $data AS $key => $value )  {
            	$_SESSION['user'][$key] = $value;	
            }
            
            $_SESSION['user']['logged_in']		= true;
            $_SESSION['user']['avatar_url'] 	= ( strlen( $_SESSION['user']['avatar_url'] ) ) ? $_SESSION['user']['avatar_url'] : SITE_DEFAULT_AVATAR_URL;
            $_SESSION['user']['profile_url'] 	= BASEURL.'/'.$_SESSION['user']['username'];
            $_SESSION['user']['full_name'] 		= $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name'];
            
            $_SESSION['SITE_DEBUG'] 			= true;
            
            // remove the user's password from the session
            unset( $_SESSION['user']['password'] );			
		}    	
    }
}