<?php

namespace Pracapl\ZnajdzPraceZPracapl;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Pracapl\ZnajdzPraceZPracapl\Repository\AppearanceSettingsRepository;

class AdminSettings
{
    public const SETTINGS_PAGE_NONCE = 'settings-page';
    public const APPEARANCE_SETTINGS_PAGE_NONCE = 'appearance-settings-page';

	public function __construct()
	{
		add_action('admin_menu', array(&$this, 'addPluginSettings'));
		add_action('admin_menu', array(&$this, 'addPluginAppearanceSettings'));
        add_action('admin_enqueue_scripts', array(&$this, 'addAppearanceSiteJavascript'));
	}

	public function addPluginSettings()
	{
		add_menu_page(
			__('Find job offers with Praca.pl', 'znajdz-prace-z-pracapl'),
			__('Find job offers with Praca.pl', 'znajdz-prace-z-pracapl'),
			'administrator',
			'zpzppl_settings',
			array(&$this, 'displaySettings')
		);
	}

	/**
	 * @side-effect render string HTML containing settings page
	 */
	public function displaySettings()
	{
        $isSave = false;

		if(!empty($_POST) && !empty($_POST['znajdz-prace-z-pracapl'])) {
            check_admin_referer(self::SETTINGS_PAGE_NONCE);

			$prPracaOptions = sanitize_post($_POST['znajdz-prace-z-pracapl'], 'db');;
			update_option('znajdz-prace-z-pracapl',$prPracaOptions);

            $isSave = true;
		} else {
			$prPracaOptions = get_option('znajdz-prace-z-pracapl');
		}
		$prView = View::get();
		$html = $prView->renderSettingsForm($prPracaOptions);

        if ($isSave) {
            $this->printSuccessMessage();
        }
		echo HtmlSanitizer::sanitizeHtml($html);
	}

	public function addPluginAppearanceSettings()
	{
		add_submenu_page(
			'zpzppl_settings',
			__('Appearance', 'znajdz-prace-z-pracapl'),
			__('Appearance', 'znajdz-prace-z-pracapl'),
			'administrator',
			'zpzppl_appearance_settings',
			array(&$this, 'displayAppearanceSettings')
		);

		// Change name of first submenu title
		global $submenu;
		$submenu['zpzppl_settings'][0][0] = __('Settings', 'znajdz-prace-z-pracapl');
	}

	/**
	 * @side-effect render string HTML containing settings subpage
	 */
	public function displayAppearanceSettings()
	{
        $isSave = false;

		if(!empty($_POST) && !empty($_POST['znzppl_appearance'])) {
            check_admin_referer(self::APPEARANCE_SETTINGS_PAGE_NONCE);

			$appearanceSettings = sanitize_post($_POST['znzppl_appearance'], 'db');
			update_option('znzppl_appearance', $appearanceSettings);

            $isSave = true;
		}
		$prView = View::get();

        $appearanceSettingsDto = AppearanceSettingsRepository::getSettings();
		$html = $prView->renderSettingsAppearanceForm($appearanceSettingsDto);

        if ($isSave) {
            $this->printSuccessMessage();
        }

		echo HtmlSanitizer::sanitizeHtml($html);
	}

    public function addAppearanceSiteJavascript($hook)
    {
        if (!is_page('zpzppl_appearance_settings')) {
            return;
        }

        wp_enqueue_script(
            'zpzppl_appearance_settings',
            zpzppl_get_asset_path('/public/js/adminAppearanceSettings.js'),
            [],
            '2.2'
        );
    }

    private function printSuccessMessage()
    {
        echo HtmlSanitizer::sanitizeHtml('
            <div class="notice notice-success">
                <p>' . __('Saved successfully', 'znajdz-prace-z-pracapl') . '</p>
            </div>');
    }
}