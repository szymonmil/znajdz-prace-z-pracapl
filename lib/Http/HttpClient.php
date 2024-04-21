<?php

namespace Pracapl\ZnajdzPraceZPracapl\Http;

class HttpClient implements HttpInterface
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
