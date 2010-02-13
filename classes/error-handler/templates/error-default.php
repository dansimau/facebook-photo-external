<?php
	$output .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">";
	$output .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	$output .= "<head>";
	$output .= "<title>Facebook Gallery Error</title>";
	$output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/style/default.css\" media=\"screen\" />";
	$output .= "</head>";
	$output .= "<body>";

	$output .= "<div class=\"error\">";
	$output .= "<h1>Error</h1>";
	$output .= "<span class=\"message\">Oops! There has been a problem with the Facebook External Gallery application :-(</span>";
	$output .= "<pre>" . $exception->__toString() . "</pre>";
	$output .= "</div>";

	$output .= "</body>";
	$output .= "</html>";
	
	print $output;
?>