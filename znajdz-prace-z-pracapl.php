<?php
/*
 * Plugin Name: Znajdż Pracę z Praca.pl
 * Description: Twoja strona internetowa może być jeszcze bardziej przyjazna dla użytkownika i nowoczesna dzięki wtyczce Wordpress Znajdź Pracę z Praca.pl. Atrakcyjny wizualnie plugin wzbogaci wygląd Twojej witryny, a wyświetlane informacje z rynku pracy uzupełnią publikowane przez Ciebie treści o nową, aktualną perspektywą.
 * Version: 2.2.2
 * Author: Praca.pl sp. z o.o.
 * Author URI: https://www.praca.pl
 * Text Domain: znajdz-prace-z-pracapl
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Pracapl\ZnajdzPraceZPracapl\AdminSettings;
use Pracapl\ZnajdzPraceZPracapl\Client;
use Pracapl\ZnajdzPraceZPracapl\HtmlSanitizer;
use Pracapl\ZnajdzPraceZPracapl\Repository\AppearanceSettingsRepository;
use Pracapl\ZnajdzPraceZPracapl\View;
use Pracapl\ZnajdzPraceZPracapl\WpView;

require __DIR__ . '/vendor/autoload.php';

// aktywacja pluginu
function zpzppl_activation() {}
register_activation_hook(__FILE__, 'zpzppl_activation');

// deaktywacja pluginu
function zpzppl_deactivation() {}
register_deactivation_hook(__FILE__, 'zpzppl_deactivation');

// odpalane w chwili uruchamiania pluginu
function zpzppl_init() {
    $pluginDir = basename(dirname(__FILE__));
    load_plugin_textdomain('znajdz-prace-z-pracapl', false, $pluginDir.'/i18n' );

    // inicjalizowanie pluginiu
    require_once 'initWordpress.php';

    wp_register_sidebar_widget(
        'znajdz-prace-z-pracapl',                      // your unique widget id
        __('Job offers', 'znajdz-prace-z-pracapl'),  // widget name
        'zpzppl_widget_show',          // callback function
        array()                         // options
    );

    wp_register_widget_control(
        'znajdz-prace-z-pracapl',
        __('Jobs Finder', 'znajdz-prace-z-pracapl'),
        'zpzppl_widget_control'
    );

}
add_action('plugins_loaded', 'zpzppl_init');


add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'zpzppl_settings_link');

function zpzppl_settings_link($links) {
    $url = get_admin_url().'admin.php?page=zpzppl_settings';
    $settings_link = '<a href="'.$url.'">'.__('Settings', 'znajdz-prace-z-pracapl').'</a>';
    array_push($links, $settings_link);
    return $links;
}



// akacja osadzenia bibliotej js
function zpzppl_scripts() {
    // uzycie biblioteki juz ostadzonej w wordpresie
    //wp_enqueue_script('jquery');
    // uzycie bibioteki zewnetrznej, zaleznej od jquery
    //wp_register_script('slidesjs_core', plugins_url('js/jquery.slides.min.js', __FILE__),array("jquery"));
    //wp_enqueue_script('slidesjs_core');
}
add_action('wp_enqueue_scripts', 'zpzppl_scripts');

// akacja osadzenia styli css (uzwyamy tej samej akcji do rejestracji naszej funkcji osadzajacej co przy js)
function zpzppl_styles() {
  wp_register_style('zpzppl_widget', plugins_url('public/css/zpzppl_widget.css', __FILE__), [], '2.0');
  wp_enqueue_style('zpzppl_widget');
}
add_action('wp_enqueue_scripts', 'zpzppl_styles');

// osadzanie styli w adminie
function zpzppl_adminStyles() {
    $pluginDir = basename(dirname(__FILE__));
    wp_register_style('zpzppl_admin', plugins_url($pluginDir.'/public/css/zpzppl_admin.css'), false, '2.0');
    wp_enqueue_style('zpzppl_admin');
}
add_action( 'admin_enqueue_scripts', 'zpzppl_adminStyles' );

// dodanie menu dla admina
new AdminSettings();

// dodanie i obsluga widzetu do sidebaru
function zpzppl_widget_show($args) {
    $prOptions = get_option('znajdz-prace-z-pracapl');
    $title = $prOptions['sidebarWidgetTitle'];

//    unset($prOptions['sidebarWidgetTitle']);
    $pracaClient = new Client();
    $prAds = $pracaClient->getPrAds($prOptions);
    $appearanceSettings = AppearanceSettingsRepository::getSettings();

    $output = View::get()->renderSidebarWidget($prAds, $prOptions, $appearanceSettings);

    extract($args);
    echo HtmlSanitizer::sanitizeHtml($before_widget);
    echo HtmlSanitizer::sanitizeHtml($before_title . $title . $after_title);
    echo HtmlSanitizer::sanitizeHtml($output);
    echo HtmlSanitizer::sanitizeHtml($after_widget);
}

function zpzppl_get_asset_path(string $pathFromBaseDir): string
{
    return plugins_url($pathFromBaseDir, __FILE__);
}

function zpzppl_widget_control() {
    $options = get_option('znajdz-prace-z-pracapl');

    if(!empty($_POST)) {
        check_admin_referer('widget-settings-form');

        $postData = sanitize_post($_POST['znajdz-prace-z-pracapl'], 'db');

        if (!empty($postData)) {
            $prPracaOptions = $postData;
            if(!empty($prPracaOptions['count'])) $options['count'] = (int) $prPracaOptions['count'];
            if(isset($prPracaOptions['show'])) {
                $options['show'] = array_filter($prPracaOptions['show'], 'htmlentities');
            } else {
                $options['show'] = array();
            }
            if(isset($prPracaOptions['sidebarWidgetTitle'])) $options['sidebarWidgetTitle'] = htmlentities($prPracaOptions['sidebarWidgetTitle']);
            update_option('znajdz-prace-z-pracapl',$options);
        }
    }
    $prView = View::get();
    $html = $prView->renderSidebarWidgetSettingsForm($options);
    echo HtmlSanitizer::sanitizeHtml($html);
}

// shortcode
function zpzppl_shortcode($atts) {
    $prOptions = get_option('znajdz-prace-z-pracapl');
    $scAtts = shortcode_atts(array(
        'class' => '',
        'count' => $prOptions['count'] ?? WpView::OFFERS_COUNT,
        'company' => $prOptions['company'] ?? null,
        'show' => implode(',', $prOptions['show'] ?? [])
    ), $atts);

    $prOptions['count'] = $scAtts['count'] ?? WpView::OFFERS_COUNT;
    $prOptions['company'] = $scAtts['company'] ?? null;
    $prOptions['show'] = explode(',', $scAtts['show'] ?? '');

    unset($prOptions['sidebarWidgetTitle']);
    $pracaClient = new Client();
    $prAds = $pracaClient->getPrAds($prOptions);

    $prOptions['class'] = $scAtts['class'];
    $output = View::get()->renderprPracaJobsShortCode($prAds, $prOptions);
    return $output;
}
add_shortcode('znajdz_prace_z_pracapl', 'zpzppl_shortcode');
