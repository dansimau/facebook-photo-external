<?php
class ErrorHandler {

    /**
     * Print error message to browser.
     */
    public function handle_exception($exception) {
    
    	header("HTTP/1.0 500 Internal Server Error");
    	
    	/* Interpret FacebookPhotoServiceException's error codes and display
    	   special error pages */
    	if ($exception instanceof FacebookPhotoServiceException) {

        	switch ($exception->getCode()) {
        	
/*        	   case FacebookPhotoServiceException::ERROR_FACEBOOK_SESSION:
        	       include("templates/error-facebook-session.php");
        	       break;*/
        	   
        	   default:
        	       include("templates/error-default.php");
            }
            
        /* If it's any other type of exception, we don't really care; display a
           standard error page. */
        } else {
            include("templates/error-default.php");
        }
    }       
}
?>