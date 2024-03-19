<?php

require_once 'iHttp.php';

class HttpCurl implements iHttp {

    private $_headers = array();
    private $_body = '';

    private $_cookieSaveFile = null;
    private $_cookieSaveDir = '/tmp';
    private $_cookieSavePrefixFile = 'co_';

    public function __construct() {
        $this->_cookieSaveFile = tempnam($this->_cookieSaveDir, $this->_cookieSavePrefixFile);
    }

    public function __destruct() {
	    wp_delete_file($this->_cookieSaveFile);
    }

    public function goToUrl($url, array $httpParams = array()) {
        $ch = $this->_initRequest($url, $httpParams);
        return $this->_finishRequest($ch);
    }

    public function sendPost($url, $data, array $httpParams = array()) {
        $ch = $this->_initRequest($url, $httpParams);
        $data = is_array($data) ? http_build_query($data) : $data;

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        return $this->_finishRequest($ch);
    }

    private function _initRequest($url, array $httpParams = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

        if (empty($httpParams['cookie'])) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookieSaveFile);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_cookieSaveFile);
        } else {
            curl_setopt($ch, CURLOPT_COOKIE, $httpParams['cookie']);
        }

        if (isset($httpParams['ssl_verify'])) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, (bool) $httpParams['ssl_verify']);
        }

        return $ch;
    }

    private function _finishRequest($ch) {
        $requestResponse = curl_exec($ch);
        $requestInfo = curl_getinfo($ch);

        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        $this->_parsePage($requestResponse, $requestInfo);

        curl_close($ch);

        return array(
            'headers' => $this->_headers,
            'body' => $this->_body
        );
    }

    private function _parsePage($requestResponse, $requestInfo) {
        $this->_headers = $this->_getHeadersFromRequstResponse(substr($requestResponse, 0, $requestInfo['header_size']));
        $this->_body = substr($requestResponse, $requestInfo['header_size']);
    }

    private function _getHeadersFromRequstResponse($textHeaders) {
        $headers = array();
        foreach (explode("\r\n", $textHeaders) as $i => $line) {
            if (empty($line)) continue;
            $tmpArr = explode(': ', $line);
            if (count($tmpArr) == 2) {
                $headers[$tmpArr[0]] = $tmpArr[1];
            } else {
                $headers['http_code'][] = $tmpArr[0];
            }
        }
        return $headers;
    }

    private function _parseCookieFile($file) {
        $aCookies = array();
        $aLines = file($file);
        foreach ($aLines as $line) {
            if ('#' == $line[0])  continue;
            $arr = explode("\t", $line);
            if (isset($arr[5]) && isset($arr[6])) {
                $aCookies[$arr[5]] = $arr[6];
            }
        }

        return $aCookies;
    }
}
