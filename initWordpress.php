<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Pracapl\ZnajdzPraceZPracapl\View;

View::setType(View::TYPE_WP);
$prView = View::get();
$prView->_ = function (string $string) { return __($string, 'znajdz-prace-z-pracapl'); };