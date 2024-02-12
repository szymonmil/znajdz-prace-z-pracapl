<?php

interface iHttp {

	public function goToUrl($url, array $httpParams = array());
	public function sendPost($url, $data, array $httpParams = array());

}