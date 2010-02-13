<?php
/**
 * Photo display - basic HTML template.
 *
 * Information available:
 *   $photos[] array
 *   $tags[] array
 *   $album[] array
 *   $user[] array
 */

if (is_null($photos)) {    

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
    
    //TODO: title

	// Output contents of buffer
    $buffer .= $output;
    echo $output;
    $output = "";
    
    foreach ($photos as $photo) {
    
	  	$output .= "<div class=\"facebookPhotoPhotoPhoto\">";
	  	$output .= "<div class=\"facebookPhotoPhotoPhotoPhoto\">";
	  	$output .= "<img src=\"" . $photo['src_big'] . "\"></img>";
	  	$output .= "</div>";
	  	$output .= "<p class=\"facebookPhotoPhotoCaption\">" . $photo['caption'] . "</p>";

		if (!empty($tags)) {
		  	$output .= "<p class=\"facebookPhotoPhotoTags\">In this photo: ";
	
			$i = 0;
		  	foreach ($tags as $tag) {
		  		// If this is the last name to list, don't put a comma
		  		if ($i == count($tags)-1) {
				  	$output .= "<span class=\"facebookPhotoPhotoTagName\">" . $tag['text'] . ".</span>";
				} else {
				  	$output .= "<span class=\"facebookPhotoPhotoTagName\">" . $tag['text'] . ",</span> ";
				}
			  	$i++;
		  	}
		  	$output .= "</p>";
		}
	  	
	  	$output .= "<p class=\"facebookPhotoPhotoCreated\">Added " . date(FB_GAL_HTML_DATE_FORMAT, $photo['created']) . "</p>";
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
?>