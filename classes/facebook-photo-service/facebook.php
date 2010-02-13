<?php
/**
 * The Facebook REST library requires these static methods.
 */
class Facebook {

	public static function get_facebook_url($subdomain='www') {
		return 'http://' . $subdomain . '.facebook.com';
	}

	public static function generate_sig($params_array, $secret) {
		$str = '';

		ksort($params_array);
		// Note: make sure that the signature parameter is not already included in
		//       $params_array.
		foreach ($params_array as $k=>$v) {
			$str .= "$k=$v";
		}
		$str .= $secret;

		return md5($str);
	}
}
?>