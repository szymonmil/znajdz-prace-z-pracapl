<?php

require_once 'WpView.php';

class ZnajdzPraceZPracapl_View {

    const TYPE_WP = 'wordpress';

    private static $_type;

    public static function get($type = null) {
        if(!$type) $type = self::$_type;
        switch($type) {
            case self::TYPE_WP: return ZnajdzPraceZPracapl_WpView::instance();
        }
    }

    public static function setType($type) {
        self::$_type = $type;
    }
}