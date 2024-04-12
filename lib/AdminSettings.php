<?php

namespace Pracapl\ZnajdzPraceZPracapl;

use Pracapl\ZnajdzPraceZPracapl\Repository\AppearanceSettingsRepository;
use ZnajdzPraceZPracapl_View;

class AdminSettings
{
	public function __construct()
	{
		add_action('admin_menu', array(&$this, 'addPluginSettings'));
		add_action('admin_menu', array(&$this, 'addPluginAppearanceSettings'));
	}

	public function addPluginSettings()
	{
		add_menu_page(
			__('Find job offers with Praca.pl', 'znajdz-prace-z-pracapl'),
			__('Find job offers with Praca.pl', 'znajdz-prace-z-pracapl'),
			'administrator',
			'znajdz_prace_z_pracapl_settings',
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
			$prPracaOptions = $_POST['znajdz-prace-z-pracapl'];
			update_option('znajdz-prace-z-pracapl',$prPracaOptions);

            $isSave = true;
		} else {
			$prPracaOptions = get_option('znajdz-prace-z-pracapl');
		}
		$prView = ZnajdzPraceZPracapl_View::get();
		$html = $prView->renderSettingsForm($prPracaOptions);

        if ($isSave) {
            $this->printSuccessMessage();
        }
		echo $html;
	}

	public function addPluginAppearanceSettings()
	{
		add_submenu_page(
			'znajdz_prace_z_pracapl_settings',
			__('Appearance', 'znajdz-prace-z-pracapl'),
			__('Appearance', 'znajdz-prace-z-pracapl'),
			'administrator',
			'znajdz_prace_z_pracapl_appearance_settings',
			array(&$this, 'displayAppearanceSettings')
		);

		// Change name of first submenu title
		global $submenu;
		$submenu['znajdz_prace_z_pracapl_settings'][0][0] = __('Settings', 'znajdz-prace-z-pracapl');
	}

	/**
	 * @side-effect render string HTML containing settings subpage
	 */
	public function displayAppearanceSettings()
	{
        $isSave = false;
		if(!empty($_POST) && !empty($_POST['znzppl_appearance'])) {
			$appearanceSettings = $_POST['znzppl_appearance'];
			update_option('znzppl_appearance', $appearanceSettings);

            $isSave = true;
		} else {
			$appearanceSettings = get_option('znzppl_appearance');
		}
		$prView = ZnajdzPraceZPracapl_View::get();

        $appearanceSettingsDto = AppearanceSettingsRepository::getSettings();
		$html = $prView->renderSettingsAppearanceForm($appearanceSettingsDto);

        if ($isSave) {
            $this->printSuccessMessage();
        }

		echo $html;
	}

    private function printSuccessMessage()
    {
        echo '
            <div class="notice notice-success">
                <p>' . __('Saved successfully', 'znajdz-prace-z-pracapl') . '</p>
            </div>';
    }
}