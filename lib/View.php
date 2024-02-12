<?php

require_once 'WpView.php';

class PrPracaView {

    const TYPE_WP = 'wordpress';

    private static $_type;

    public static function get($type = null) {
        if(!$type) $type = self::$_type;
        switch($type) {
            case self::TYPE_WP: return PrPracaWpView::instance();
        }
    }

    public static function setType($type) {
        self::$_type = $type;
    }
}