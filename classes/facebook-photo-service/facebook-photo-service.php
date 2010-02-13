<?php

// DataStore module
require_once("datastore/datastore.php");
require_once("datastore/datastore-cache.php");

// Facebook connection layer
require_once("facebook.php");
require_once("facebook/facebookapi_php5_restlib.php");

/**
 * FacebookPhotoService.
 */
class FacebookPhotoService {

	public $facebook;
	public $session_manager;
	public $cache_manager;
	
	public $display_template;
	
	public function __construct() {
	
	    // Create Facebook connection
	    $this->facebook = new FacebookRestClient(FB_GAL_FACEBOOK_API_KEY, FB_GAL_FACEBOOK_API_SECRET);
	    
	    // Create cache managers
    	$this->session_manager = new DataStore(FB_GAL_SESSION_CACHE_DIR);
    	$this->cache_manager = new CacheDataStore(FB_GAL_CACHE_DIR, FB_GAL_CACHE_TTL);
    	
    	// Defaults
    	$this->display_template = "html-basic";
	}
	
	/**
	 * Process an authorisation token from Facebook and store the session key so we
	 * can access this user's information in the future.
	 *
	 * @param $auth_token the authorisation token provided by the Facebook callback.
	 * @return            true if the auth token was valid and the session key was
	 *                    stored; false if the process failed.
	 */
	public function new_user_session($auth_token) {

    	// Get info from Facebook
    	$result = $this->facebook->auth_getSession($auth_token);

		/* Double check we have all the info we need before continuing. If not, it
           means we've been given an invalid auth_token. */ 
		if (empty($result["uid"]) || empty($result["session_key"])) {
			return false;
			
		} else {

    		// Save the session key for this user
    		$this->session_manager->put_item($result["uid"], $result["session_key"]);    
            return true;
        }
    }	
	
	/**
	 * Retrieves the specified photo album from Facebook and displays it.
	 *
	 * @param $uid the ID of the user who the album belongs to
	 * @param $aid the ID of the album to retrieve
	 */
	public function get_album($uid, $aid) {

        if (!$this->validate_user_session($uid)) throw new FacebookPhotoServiceException("Session is expired or not valid.", FacebookPhotoServiceException::ERROR_FACEBOOK_SESSION);

        // Cache "hash"
   		$cache_item = $uid . "/albums/" . $aid . "_" . $this->display_template;
    		
   		// Query the cache to see if we have this album cached
   		if ($this->cache_manager->is_fresh($cache_item)) {
    		
			// Sweet! Lets retrieve the data from the cache, and show it
			echo $this->cache_manager->get_item($cache_item);
    			
		} else {
    		
    		try {

    			// Get the data from Facebook
    			$photos = $this->facebook->photos_get("", $aid, "");

    			// Get album info
    			$album = $this->facebook->photos_getAlbums("$uid", "$aid");

    			// Get user info
    			$user = $this->facebook->users_getInfo("$uid", "name");
            }
            catch (Exception $e) {
                throw new FacebookPhotoServiceException("Error while retrieving album information from Facebook.", FacebookPhotoServiceException::ERROR_FACEBOOK_GET_ALBUM);
            }
            
   			// Pass handling of the data to the template
   			include "templates/" . $this->display_template . "/photolist.php";
    	}
	}
	
	/**
	 * Retrieves the specified photo from Facebook and outputs it.
	 *
	 * @param $uid  the ID of the user who the photo belongs to
	 * @param $pid  the ID of the photo to retrieve
	 */
	public function get_photo($uid, $aid, $pid) {

        if (!$this->validate_user_session($uid)) throw new FacebookPhotoServiceException("Session is expired or not valid.", FacebookPhotoServiceException::ERROR_FACEBOOK_SESSION);

        // Cache "hash"
   		$cache_item = $uid . "/photos/" . $pid . "_" . $this->display_template;
   		
   		// Query the cache to see if we have a fresh version of this album list
   		if ($this->cache_manager->is_fresh($cache_item)) {
   		
   			// Sweet! Lets retrieve the data from the cache, and list it
   			echo $this->cache_manager->get_item($cache_item);
   			
   		} else {

            try {   		
       			// Get the photo data from Facebook
    			$photos = $this->facebook->photos_get("", "$aid", "$pid");
    			
    			// Get people tagged in this photo
    			$tags = $this->facebook->photos_getTags("$pid");
    			
    			// Get album info
    			$album = $this->facebook->photos_getAlbums("$uid", "$aid");
    			
    			// Get user info
    			$user = $this->facebook->users_getInfo("$uid", "name");
            }
            catch (Exception $e) {
                throw new FacebookPhotoServiceException("Error while retrieving photo information from Facebook.", FacebookPhotoServiceException::ERROR_FACEBOOK_GET_PHOTO);
            }
   			
   			// Pass handling of the data to the template
   			include "templates/" . $this->display_template . "/photo.php";
       	}
    }

	/**
	 * Retrieves the list of photo albums from Facebook for the specified user and
	 * outputs them.
	 *
	 * @param $uid  the ID of the user who the photo belongs to
	 */

    public function get_album_list($uid) {

        if (!$this->validate_user_session($uid)) throw new FacebookPhotoServiceException("Session is expired or not valid.", FacebookPhotoServiceException::ERROR_FACEBOOK_SESSION);

        // Cache "hash"
		$cache_item = $uid . "/albumlist" . "_" . $this->display_template;
		
		// Query the cache to see if we have this album list
		if ($this->cache_manager->is_fresh($cache_item)) {
		
			// Sweet! Lets retrieve the data from the cache, and list it
			echo $this->cache_manager->get_item($cache_item);
			
		} else {
			
			try {
    			// Get the data from Facebook
    			$albums = $this->facebook->photos_getAlbums($uid,"");

    			// Get user info
    			$user = $this->facebook->users_getInfo("$uid", "name");
            }
            catch (Exception $e) {
                throw new FacebookPhotoServiceException("Error while retrieving photo information from Facebook.", FacebookPhotoServiceException::ERROR_FACEBOOK_GET_PHOTO);
            }

   			// Pass handling of the data to the template
   			include "templates/" . $this->display_template . "/albumlist.php";
		}
    }
	
    public function validate_user_session($uid) {
    
	    // See if we have a session ID for this user ID in the cache
	    if (!$this->session_manager->item_exists($uid)) return false;
	    
        /* Retrieve session key and check if it's still valid with Facebook, and
        	also that the user ID returned from Facebook matches the user ID
        	provided */
    	$this->facebook->session_key = $this->session_manager->get_item($uid);
        if ($this->facebook->users_getLoggedInUser() == $uid) {
            return true;
        } else {
            return false;
        }
    }
} // End class


class FacebookPhotoServiceException extends Exception {

	// Exception error codes
	const ERROR_UNKNOWN                = 0;
	const ERROR_FACEBOOK_AUTH          = 1;
	const ERROR_FACEBOOK_SESSION       = 2;
	const ERROR_FACEBOOK_GET_PHOTO     = 3;
	const ERROR_FACEBOOK_GET_ALBUM     = 4;
	const ERROR_FACEBOOK_GET_ALBUMLIST = 5;

    // Making message and error code required
    public function __construct($message, $code) {   
        parent::__construct($message, $code);
    }

}
