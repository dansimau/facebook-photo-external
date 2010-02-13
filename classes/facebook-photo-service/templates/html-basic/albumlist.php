<?php
/**
 * Album display - basic HTML template.
 *
 * Information available:
 *   $albums[] array
 */


if (empty($albums)) {
	// TODO: Fix this!!
	echo 'No photos albums!';

} else {

    $buffer = "";
    $output = "";

	$output .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">";
	$output .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	$output .= "<head>";
	$output .= "<title>" . $user[0]['name'] . "'s photos</title>";
	$output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/style/default.css\" media=\"screen\" />";
	$output .= "</head>";
	$output .= "<body>";

	// Output contents of buffer
    $buffer .= $output;
    echo $output;
    $output = "";
	
	$i = 0;
	foreach ($albums as $album) {
	
		// Get cover pic for this album
		$album_cover_photo = $this->facebook->photos_get("","",$album['cover_pid']);		
	
		$output .= "<div class=\"facebookPhotoAlbumsAlbum\">";
		$output .= "<div class=\"facebookPhotoAlbumsAlbumCover\">";
		$output .= "<a href=\"" . $album['aid'] . "/?" . $_SERVER['QUERY_STRING'] . "\"><img src=\"" . $album_cover_photo['0']['src'] . "\"></img></a>";
		$output .= "</div>";
		$output .= "<p class=\"facebookPhotoAlbumsAlbumName\"><a href=\"" . $album['aid'] . "/?" . $_SERVER['QUERY_STRING'] . "\">" . $album['name'] . "</a></p>";
		$output .= "<p class=\"facebookPhotoAlbumsAlbumSize\">" . $album['size'] . " photos</p>";
		$output .= "<p class=\"facebookPhotoAlbumsAlbumCreated\">Created " . date(FB_GAL_HTML_DATE_FORMAT, $album['created']) . "</p>";
		$output .= "<p class=\"facebookPhotoAlbumsAlbumUpdated\">Last updated " . date(FB_GAL_HTML_DATE_FORMAT, $album['modified']) . "</p>";
		$output .= "</div>";
	
		// Output contents of buffer
	    $buffer .= $output;
	    echo $output;
	    $output = "";
	    
	    $i++;
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