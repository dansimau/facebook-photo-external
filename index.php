<?php
require_once("config.inc.php");
require_once("classes/facebook-photo-service/facebook-photo-service.php");
require_once("classes/error-handler/error-handler.php");

/* Define the global error handler function. This will ensure all exceptions
   display a pretty error message on the frontend (important for a web app!) */
set_exception_handler(array("ErrorHandler", "handle_exception"));

// The magic starts here
$facebook_photo_service = new FacebookPhotoService();

// If we've got an auth token from Facebook, we're setting up a new user account
if (isset($_GET["auth_token"])) {

    $facebook_photo_service->new_user_session($_GET["auth_token"]);
    
//    if ($facebook_photo_service->new_user_session($_GET["auth_token"])) {
//    
//        // User session was stored successfully. Print happy message.
//        include("templates/global_header.php");
//        include("templates/account_created_success.php");
//        exit;
//        
//    } else {
//
//        // Problem storing user's session key. Print sad message.
//        include("templates/global_header.php");
//        include("templates/account_created_failed.php");
//        exit;
//    }
}


/* A user is signing up or logging into Facebook to give us a session key. Let's
   redirect them to Facebook to get started. */
if (isset($_GET['authenticate'])) {
	header("Location: " . Facebook::get_facebook_url() . "/login.php?v=1.0&api_key=" . FB_GAL_FACEBOOK_API_KEY);
	exit;
}

// Get uid/aid/pid variables from REQUEST_URI
$uri = $_SERVER["REQUEST_URI"];
$uri = preg_replace("/(.*)\?.*$/", "$1", $uri);		// Strip out the query string from the URI
$uri = str_replace("//", "/", $uri);				// Replace double-slashes with a single-slash (mimics Apache behaviour)

// Add trailing slash if it's not there (mimics Apache's behaviour)
if (substr($uri, strlen($uri)-1) != "/") {

	$dest_uri = $uri . "/";

	if ($_SERVER["QUERY_STRING"]) {
		$dest_uri .= "?" . $_SERVER["QUERY_STRING"];
	}
   	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $dest_uri);
	exit;
}

/* Decode uid/aid/pid from the URI */

$vars = trim($uri, "/");	// Trim slashes from beginning and end (to prevent empty array elements in upcoming split)
$vars = split("/", $vars);	// Split string at slashes

$uid = $vars[0];
$aid = $vars[1];
$pid = $vars[2];

/* Change template based on query string? */
switch ($_GET['output']) {

	case "html-basic-e63388c3fd419d10f7d8fe89e7495567":
		$facebook_photo_service->display_template = "html-custom-casey";
		break;

	default:
		$facebook_photo_service->display_template = "html-basic";
}

/* Work out what to display. */
if (!empty($pid)) {

	// Display photo
	$facebook_photo_service->get_photo($uid, $aid, $pid);

} elseif (!empty($aid)) {

	// Display photo album
	$facebook_photo_service->get_album($uid, $aid);

} elseif (!empty($uid)) {

	// Display albumlist
	$facebook_photo_service->get_album_list($uid);

} else {

	// Redirect to main site
   	header("HTTP/1.1 301 Moved Temporarily");
	header("Location: http://www.fbgallery.com/");
	exit;
}

?>