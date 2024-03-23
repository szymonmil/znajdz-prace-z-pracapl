<?php

require_once 'Http/HttpClient.php';

class ZnajdzPraceZPracapl_Client {

    const ADDR_GET_ADS = 'http://www.praca.pl/api/getadswp?';

    private $_http = null;

    public function __construct() {
        $this->_http = new ZnajdzPraceZPracapl_HttpClient();
    }

    /**
     * Pobranie ogloszen na podstawie przekazanych parametrow
     * @param $prOptions
     * @return array|mixed
     */
    public function getPrAds($prOptions) {
        $prAds = $this->_http->goToUrl(self::ADDR_GET_ADS.http_build_query($prOptions));
        $prAds = json_decode($prAds['body'], true);
        return $prAds;
    }
}