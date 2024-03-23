<?php

require_once __DIR__.'/lib/View.php';
require_once __DIR__.'/lib/Client.php';

ZnajdzPraceZPracapl_View::setType(ZnajdzPraceZPracapl_View::TYPE_WP);
$prView = ZnajdzPraceZPracapl_View::get();
$prView->_ = function (string $string) { return __($string, 'znajdz-prace-z-pracapl'); };