<?php

require_once __DIR__.'/lib/View.php';
require_once __DIR__.'/lib/Client.php';

PrPracaView::setType(PrPracaView::TYPE_WP);
$prView = PrPracaView::get();
$prView->_ = function (string $string) { return __($string, 'znajdz-prace-z-pracapl'); };