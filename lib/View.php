<?php

namespace Pracapl\ZnajdzPraceZPracapl;

class View {

    const TYPE_WP = 'wordpress';

    private static $_type;

    public static function get($type = null) {
        if(!$type) $type = self::$_type;
        switch($type) {
            case self::TYPE_WP: return WpView::instance();
        }
    }

    public static function setType($type) {
        self::$_type = $type;
    }
}