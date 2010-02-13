<?php
// Facebook API key and secret
define("FB_GAL_FACEBOOK_API_KEY", "");
define("FB_GAL_FACEBOOK_API_SECRET", "");

// Facebook session key cache dir
define("FB_GAL_SESSION_CACHE_DIR", "data/facebook_session_keys");

// Content cache dir
define("FB_GAL_CACHE_DIR", "data/cache");

// Time (in seconds) before cached content is expired
define("FB_GAL_CACHE_TTL", 600);

// Date format for HTML printing (see PHP manual)
define("FB_GAL_HTML_DATE_FORMAT", DATE_RFC822);

// Uncomment to enable Facebook debugging on the frontend
//$GLOBALS['facebook_config']['debug'] = 1;
?>
