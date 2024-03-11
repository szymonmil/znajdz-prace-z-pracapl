<?php
/*
 * Plugin Name: Oferty pracy z Praca.pl
 * Plugin URI: http://wordpress.org/plugins/oferty-pracy-z-pracapl/
 * Description: Oferty pracy z Praca.pl to idealny sposób na wzbogacenie Twojej strony internetowej aktualnymi ofertami pracy pochodzącymi z portalu Praca.pl.
 * Version: 1.2.2
 * Author: Praca.pl sp. z o.o.
 * Author URI: https://www.praca.pl
 * Text Domain: prPraca
*/

// aktywacja pluginu
function prPraca_activation() {}
register_activation_hook(__FILE__, 'prPraca_activation');

// deaktywacja pluginu
function prPraca_deactivation() {}
register_deactivation_hook(__FILE__, 'prPraca_deactivation');

// odpalane w chwili uruchamiania pluginu
function prPraca_init() {
    $pluginDir = basename(dirname(__FILE__));
    load_plugin_textdomain('prPraca', false, $pluginDir.'/i18n' );

    // inicjalizowanie pluginiu
    require_once 'initWordpress.php';

    wp_register_sidebar_widget(
        'prPraca',                      // your unique widget id
        __('Jobs offerts', 'prPraca'),  // widget name
        'prPraca_widget_show',          // callback function
        array()                         // options
    );

    wp_register_widget_control(
        'prPraca',
        __('Jobs Finder', 'prPraca'),
        'prPraca_widget_control'
    );

}
add_action('plugins_loaded', 'prPraca_init');


add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'plugin_settings_link');

function plugin_settings_link($links) {
    $url = get_admin_url().'admin.php?page=prPraca_settings';
    $settings_link = '<a href="'.$url.'">'.__('Settings', 'prPraca').'</a>';
    array_push($links, $settings_link);
    return $links;
}



// akacja osadzenia bibliotej js
function prPraca_scripts() {
    // uzycie biblioteki juz ostadzonej w wordpresie
    //wp_enqueue_script('jquery');
    // uzycie bibioteki zewnetrznej, zaleznej od jquery
    //wp_register_script('slidesjs_core', plugins_url('js/jquery.slides.min.js', __FILE__),array("jquery"));
    //wp_enqueue_script('slidesjs_core');
}
add_action('wp_enqueue_scripts', 'prPraca_scripts');

// akacja osadzenia styli css (uzwyamy tej samej akcji do rejestracji naszej funkcji osadzajacej co przy js)
function prPraca_styles() {
  wp_register_style('prPraca', plugins_url('public/css/prPraca.css', __FILE__));
  wp_enqueue_style('prPraca');
}
add_action('wp_enqueue_scripts', 'prPraca_styles');

// osadzanie styli w adminie
function prPraca_adminStyles() {
    $pluginDir = basename(dirname(__FILE__));
    wp_register_style( 'prPracaAdmin', plugins_url($pluginDir.'/public/css/prPracaAdmin.css'), false, '1.0.1' );
    wp_enqueue_style( 'prPracaAdmin' );
}
add_action( 'admin_enqueue_scripts', 'prPraca_adminStyles' );

// dodanie menu dla admina
function prPraca_plugin_settings() {
    add_menu_page(
        __('PrPraca', 'prPraca'),     // the title used in your HTML page
        __('PrPraca', 'prPraca'),     // the title used for the menu item on the left menu
        'administrator',            // is the capability needed to add the page. You can keep it as administrator in most of the cases..
        'prPraca_settings',         // is a unique key used for the menu item
        'prPraca_display_settings'  // is the function name used to implement the HTML form.
    );
}
add_action('admin_menu', 'prPraca_plugin_settings');

function prPraca_display_settings() {
    if(!empty($_POST) && !empty($_POST['prPraca'])) {
        $prPracaOptions = $_POST['prPraca'];
        update_option('prPraca',$prPracaOptions);
    } else {
        $prPracaOptions = get_option('prPraca');
    }
    $prView = PrPracaView::get();
    $html = $prView->renderSettingsForm($prPracaOptions);
    echo $html;
}

// dodanie i obsluga widzetu do sidebaru
function prPraca_widget_show($args) {
    $prOptions = get_option('prPraca');
    $title = $prOptions['sidebarWidgetTitle'];

//    unset($prOptions['sidebarWidgetTitle']);
    $pracaClient = new PrPracaClient();
    $prAds = $pracaClient->getPrAds($prOptions);
    $output = PrPracaView::get()->renderSidebarWidget($prAds, $prOptions);

    extract($args);
    echo $before_widget;
    echo $before_title . $title . $after_title;
    echo $output;
    echo $after_widget;
}

function prPraca_widget_control() {
    $options = get_option('prPraca');
    if(!empty($_POST) && !empty($_POST['prPraca'])) {
        $prPracaOptions = $_POST['prPraca'];
        if(!empty($prPracaOptions['count'])) $options['count'] = (int) $prPracaOptions['count'];
        if(isset($prPracaOptions['show'])) {
            $options['show'] = array_filter($prPracaOptions['show'], 'htmlentities');
        } else {
            $options['show'] = array();
        }
        if(isset($prPracaOptions['sidebarWidgetTitle'])) $options['sidebarWidgetTitle'] = htmlentities($prPracaOptions['sidebarWidgetTitle']);
        update_option('prPraca',$options);
    }
    $prView = PrPracaView::get();
    $html = $prView->renderSidebarWidgetSettingsForm($options);
    echo $html;
}

// shortcode
function prPracaJobs($atts) {
    $prOptions = get_option('prPraca');
    $scAtts = shortcode_atts(array(
        'class' => '',
        'count' => $prOptions['count'],
        'company' => $prOptions['company'],
        'show' => implode(',', $prOptions['show'])
    ), $atts);

    $prOptions['count'] = $scAtts['count'];
    $prOptions['company'] = $scAtts['company'];
    $prOptions['show'] = explode(',', $scAtts['show']);

    unset($prOptions['sidebarWidgetTitle']);
    $pracaClient = new PrPracaClient();
    $prAds = $pracaClient->getPrAds($prOptions);

    $prOptions['class'] = $scAtts['class'];
    $output = PrPracaView::get()->renderprPracaJobsShortCode($prAds, $prOptions);
    return $output;
}
add_shortcode('prPraca', 'prPracaJobs');
