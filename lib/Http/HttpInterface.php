<?php

namespace Pracapl\ZnajdzPraceZPracapl\Http;

interface HttpInterface
{
	public function goToUrl($url, array $httpParams = array());
}