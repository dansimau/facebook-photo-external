<?php
/**
 * Album photo display - basic HTML template.
 *
 * Information available:
 *   $photos[] array
 *   $album[] array
 *   $user[] array;
 */

if (empty($album)) {

	// TODO: Fix this!!
	echo 'No photos in this album!';

} else {	

	$buffer = "";
	$output = "";
	
	$output .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">";
	$output .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	$output .= "<head>";
    $output .= "<title>" . $album[0]['name'] . " - " . $user[0]['name'] . "'s photos</title>";
	$output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/style/default.css\" media=\"screen\" />";
	$output .= "</head>";
	$output .= "<body>";
	
	$output .= "<h1>" . $album[0]['name'] . "</h1>";
	
	// Output contents of buffer
    $buffer .= $output;
    echo $output;
    $output = "";
	
	foreach ($photos as $photo) {
	
		$output .= "<div class=\"facebookPhotoAlbumPhoto\">";
		$output .= "<div class=\"facebookPhotoAlbumPhotoCover\">";
		$output .= "<a href=\"" . $photo['pid'] . "/?" . $_SERVER['QUERY_STRING'] . "\">";
		$output .= "<img src=\"" . $photo['src'] . "\"></img>";
		$output .= "</a>";
		$output .= "</div>";
		$output .= "</div>";
	
		// Output contents of buffer
	    $buffer .= $output;
	    echo $output;
	    $output = "";	
	}
	
	$output .= "</body>";
	$output .= "</html>";

	// Output contents of buffer
    $buffer .= $output;
    echo $output;
    $output = "";

	// Cache the output
    $this->cache_manager->put_item($cache_item, $buffer);
}