<?php
/*
 * Plugin Name: Znajdż Pracę z Praca.pl
 * Description: Twoja strona internetowa może być jeszcze bardziej przyjazna dla użytkownika i nowoczesna dzięki wtyczce Wordpress Znajdź Pracę z Praca.pl. Atrakcyjny wizualnie plugin wzbogaci wygląd Twojej witryny, a wyświetlane informacje z rynku pracy uzupełnią publikowane przez Ciebie treści o nową, aktualną perspektywą.
 * Version: 2.0
 * Author: Praca.pl sp. z o.o.
 * Author URI: https://www.praca.pl
 * Text Domain: znajdz-prace-z-pracapl
*/

use Pracapl\ZnajdzPraceZPracapl\AdminSettings;
use Pracapl\ZnajdzPraceZPracapl\Repository\AppearanceSettingsRepository;

require __DIR__ . '/vendor/autoload.php';

// aktywacja pluginu
function znajdz_prace_z_pracapl_activation() {}
register_activation_hook(__FILE__, 'znajdz_prace_z_pracapl_activation');

// deaktywacja pluginu
function znajdz_prace_z_pracapl_deactivation() {}
register_deactivation_hook(__FILE__, 'znajdz_prace_z_pracapl_deactivation');

// odpalane w chwili uruchamiania pluginu
function znajdz_prace_z_pracapl_init() {
    $pluginDir = basename(dirname(__FILE__));
    load_plugin_textdomain('znajdz-prace-z-pracapl', false, $pluginDir.'/i18n' );

    // inicjalizowanie pluginiu
    require_once 'initWordpress.php';

    wp_register_sidebar_widget(
        'znajdz-prace-z-pracapl',                      // your unique widget id
        __('Job offers', 'znajdz-prace-z-pracapl'),  // widget name
        'znajdz_prace_z_pracapl_widget_show',          // callback function
        array()                         // options
    );

    wp_register_widget_control(
        'znajdz-prace-z-pracapl',
        __('Jobs Finder', 'znajdz-prace-z-pracapl'),
        'znajdz_prace_z_pracapl_widget_control'
    );

}
add_action('plugins_loaded', 'znajdz_prace_z_pracapl_init');


add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'plugin_settings_link');

function plugin_settings_link($links) {
    $url = get_admin_url().'admin.php?page=znajdz_prace_z_pracapl_settings';
    $settings_link = '<a href="'.$url.'">'.__('Settings', 'znajdz-prace-z-pracapl').'</a>';
    array_push($links, $settings_link);
    return $links;
}



// akacja osadzenia bibliotej js
function znajdz_prace_z_pracapl_scripts() {
    // uzycie biblioteki juz ostadzonej w wordpresie
    //wp_enqueue_script('jquery');
    // uzycie bibioteki zewnetrznej, zaleznej od jquery
    //wp_register_script('slidesjs_core', plugins_url('js/jquery.slides.min.js', __FILE__),array("jquery"));
    //wp_enqueue_script('slidesjs_core');
}
add_action('wp_enqueue_scripts', 'znajdz_prace_z_pracapl_scripts');

// akacja osadzenia styli css (uzwyamy tej samej akcji do rejestracji naszej funkcji osadzajacej co przy js)
function znajdz_prace_z_pracapl_styles() {
  wp_register_style('znajdz_prace_z_pracapl_widget', plugins_url('public/css/znajdz_prace_z_pracapl_widget.css', __FILE__), [], '2.0');
  wp_enqueue_style('znajdz_prace_z_pracapl_widget');
}
add_action('wp_enqueue_scripts', 'znajdz_prace_z_pracapl_styles');

// osadzanie styli w adminie
function znajdz_prace_z_pracapl_adminStyles() {
    $pluginDir = basename(dirname(__FILE__));
    wp_register_style('znajdz_prace_z_pracapl_admin', plugins_url($pluginDir.'/public/css/znajdz_prace_z_pracapl_admin.css'), false, '2.0');
    wp_enqueue_style('znajdz_prace_z_pracapl_admin');
}
add_action( 'admin_enqueue_scripts', 'znajdz_prace_z_pracapl_adminStyles' );

// dodanie menu dla admina
new AdminSettings();

// dodanie i obsluga widzetu do sidebaru
function znajdz_prace_z_pracapl_widget_show($args) {
    $prOptions = get_option('znajdz-prace-z-pracapl');
    $title = $prOptions['sidebarWidgetTitle'];

//    unset($prOptions['sidebarWidgetTitle']);
    $pracaClient = new ZnajdzPraceZPracapl_Client();
    $prAds = $pracaClient->getPrAds($prOptions);
    $appearanceSettings = AppearanceSettingsRepository::getSettings();

    $output = ZnajdzPraceZPracapl_View::get()->renderSidebarWidget($prAds, $prOptions, $appearanceSettings);

    extract($args);
    echo $before_widget;
    echo $before_title . $title . $after_title;
    echo $output;
    echo $after_widget;
}

function znajdz_prace_z_pracapl_widget_control() {
    $options = get_option('znajdz-prace-z-pracapl');
    if(!empty($_POST) && !empty($_POST['znajdz-prace-z-pracapl'])) {
        $prPracaOptions = $_POST['znajdz-prace-z-pracapl'];
        if(!empty($prPracaOptions['count'])) $options['count'] = (int) $prPracaOptions['count'];
        if(isset($prPracaOptions['show'])) {
            $options['show'] = array_filter($prPracaOptions['show'], 'htmlentities');
        } else {
            $options['show'] = array();
        }
        if(isset($prPracaOptions['sidebarWidgetTitle'])) $options['sidebarWidgetTitle'] = htmlentities($prPracaOptions['sidebarWidgetTitle']);
        update_option('znajdz-prace-z-pracapl',$options);
    }
    $prView = ZnajdzPraceZPracapl_View::get();
    $html = $prView->renderSidebarWidgetSettingsForm($options);
    echo $html;
}

// shortcode
function znajdz_prace_z_pracapl_shortcode($atts) {
    $prOptions = get_option('znajdz-prace-z-pracapl');
    $scAtts = shortcode_atts(array(
        'class' => '',
        'count' => $prOptions['count'] ?? ZnajdzPraceZPracapl_WpView::OFFERS_COUNT,
        'company' => $prOptions['company'] ?? null,
        'show' => implode(',', $prOptions['show'] ?? [])
    ), $atts);

    $prOptions['count'] = $scAtts['count'] ?? ZnajdzPraceZPracapl_WpView::OFFERS_COUNT;
    $prOptions['company'] = $scAtts['company'] ?? null;
    $prOptions['show'] = explode(',', $scAtts['show'] ?? '');

    unset($prOptions['sidebarWidgetTitle']);
    $pracaClient = new ZnajdzPraceZPracapl_Client();
    $prAds = $pracaClient->getPrAds($prOptions);

    $prOptions['class'] = $scAtts['class'];
    $output = ZnajdzPraceZPracapl_View::get()->renderprPracaJobsShortCode($prAds, $prOptions);
    return $output;
}
add_shortcode('znajdz_prace_z_pracapl', 'znajdz_prace_z_pracapl_shortcode');
