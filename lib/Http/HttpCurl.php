<?php

require_once 'iHttp.php';

class HttpCurl implements iHttp
{
	/**
	 * @return array{body: array}
	 */
    public function goToUrl($url, array $httpParams = array()): array {
	    $response = wp_remote_get( $url, $httpParams );

	    if ( is_wp_error( $response ) ) {
		    return [ 'body' => [] ];
	    }

	    return $response;
    }
}
