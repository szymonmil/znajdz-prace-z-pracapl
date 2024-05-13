<?php

namespace Pracapl\ZnajdzPraceZPracapl;

use DateTime;
use Pracapl\ZnajdzPraceZPracapl\Dto\AppearanceSettings;
use Pracapl\ZnajdzPraceZPracapl\View\Country;
use Pracapl\ZnajdzPraceZPracapl\View\JobCategory;
use Pracapl\ZnajdzPraceZPracapl\View\Regions;

class WpView implements ViewInterface {

    public const OFFERS_COUNT = 10;

    private static $_instance = null;

    public static function instance() {
        if(!self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __construct() {}

    public function renderSettingsForm($options = array()) {

        if(!$options) $options = array();
        $jobCategory = $regions = $show = $country = array();

        if(isset($options['country']) && is_array($options['country'])) {
            $country = $options['country'];
        }
        if(isset($options['jobcategory']) && is_array($options['jobcategory'])) {
            $jobCategory = $options['jobcategory'];
        }
        if(isset($options['region']) && is_array($options['region'])) {
            $regions = $options['region'];
        }
        if(isset($options['show']) && is_array($options['show'])) {
            $show = array_filter($options['show'], 'htmlentities');
        }

        $searchCount = !empty($options['count']) ? (int) $options['count'] : self::OFFERS_COUNT;
        $searchPhrase = !empty($options['phrase']) ? htmlentities($options['phrase']) : '';
        $searchCity = !empty($options['city']) ? htmlentities($options['city']) : '';
        $searchECompany = !empty($options['ecompany']) ? htmlentities($options['ecompany']) : '';
        $searchCompany = !empty($options['company']) ? htmlentities($options['company']) : '';
        $prPracaSidebarWidgetTitle = !empty($options['sidebarWidgetTitle']) ? htmlentities($options['sidebarWidgetTitle']) : '';

        $html =
                '<div id="prPraca" class="plugin-settings">
                    <div id="prPracaSettings">
                        <h2><div class="pr-icon32"> </div>'.__('Settings', 'znajdz-prace-z-pracapl').'</h2>
                        <p class="prPracaInfoBox">'.__('To display the plugin on your website, go to the "Appearance" bookmark and then click on "Widgets".  Activate the "Jobs from Praca.pl" wigdet, for example by dragging and dropping it in the Sidebar.', 'znajdz-prace-z-pracapl').'</p>
                        <form method="post" action="#" id="widgetSettings">
                            <input type="hidden" value="'.$prPracaSidebarWidgetTitle.'" name="znajdz-prace-z-pracapl[sidebarWidgetTitle]">';
                            $html .= '<div>
                                <h3>'.__('Search params', 'znajdz-prace-z-pracapl').'</h3>

                                    <h4>'.__('Phrase (optional):', 'znajdz-prace-z-pracapl').'</h4>
                                    <label><input type="text" value="'.$searchPhrase.'" name="znajdz-prace-z-pracapl[phrase]"></label>
                                    <div>'.__('Position, company, keyword.', 'znajdz-prace-z-pracapl').'</div>

                                    <h4>'.__('City (optional):', 'znajdz-prace-z-pracapl').'</h4>
                                    <label><input type="text" value="'.$searchCity.'" name="znajdz-prace-z-pracapl[city]"></label>
                                    <div>'.__('Provide the name of the city.', 'znajdz-prace-z-pracapl').'</div>

                                    <h4>'.__('Company ID (optional):', 'znajdz-prace-z-pracapl').'</h4>
                                    <label><input type="text" value="'.$searchCompany.'" name="znajdz-prace-z-pracapl[company]"></label>
                                    <div>'.__('Current job offers from the specified employer on Praca.pl will be displayed.', 'znajdz-prace-z-pracapl').'</div>
                                    <div>'.__('You can provide several company IDs, separating them with a semicolon, e.g. 1234;1234', 'znajdz-prace-z-pracapl').'</div>

                                    <h4>'.__('Company ID to exclude (optional):', 'znajdz-prace-z-pracapl').'</h4>
                                    <label><input type="text" value="'.$searchECompany.'" name="znajdz-prace-z-pracapl[ecompany]"></label>
                                    <div>'.__('Job offers from the specified employer on Praca.pl will not be displayed.', 'znajdz-prace-z-pracapl').'</div>
                                    <div>'.__('You can provide several company IDs, separating them with a semicolon, e.g. 1234;1234', 'znajdz-prace-z-pracapl').'</div>
                                </div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.__('Category (optional):', 'znajdz-prace-z-pracapl').'</h3>';
                                foreach(JobCategory::items() as $jcKey => $jcValue) {
                                    $checked = in_array($jcKey, $jobCategory) ? 'checked="checked"' : '';
                                    $html .= '<label><input type="checkbox" value="'.$jcKey.'" name="znajdz-prace-z-pracapl[jobcategory][]" '.$checked.'>'.$jcValue.'</label>';
                                }
                            $html .= '</div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.__('Region (optional):', 'znajdz-prace-z-pracapl').'</h3>';
                                $html .= '<div class="pr-region-main">';
                                    foreach(Regions::items() as $rKey => $rValue) {
                                        if($rKey > 16 || $rKey == 0) {
                                            $checked = in_array($rKey, $regions) ? 'checked="checked"' : '';
                                            $html .= '<label><input type="checkbox" value="'.$rKey.'" name="znajdz-prace-z-pracapl[region][]" '.$checked.'>'.$rValue.'</label>';
                                        }
                                    }
                                $html .= '</div>';
                                $html .= '<div class="pr-region-details">';
                                    foreach(Regions::items() as $rKey => $rValue) {
                                        if($rKey > 0 && $rKey <= 16) {
                                            $checked = in_array($rKey, $regions) ? 'checked="checked"' : '';
                                            $html .= '<label><input type="checkbox" value="'.$rKey.'" name="znajdz-prace-z-pracapl[region][]" '.$checked.'>'.$rValue.'</label>';
                                        }
                                    }
                                $html .= '</div>';
                            $html .= '</div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.__('Country (optional):', 'znajdz-prace-z-pracapl').'</h3>';
                                foreach(Country::items() as $cKey => $cValue) {
                                    $checked = in_array($cKey, $country) ? 'checked="checked"' : '';
                                    $html .= '<label><input type="checkbox" value="'.$cKey.'" name="znajdz-prace-z-pracapl[country][]" '.$checked.'>'.$cValue.'</label>';
                                }
                            $html .= '</div>';

                            $checkedShowDate = in_array('date', $show) ? 'checked="checked"' : '';
                            $checkedShowCompany = in_array('company', $show) ? 'checked="checked"' : '';
                            $checkedShowCity = in_array('city', $show) ? 'checked="checked"' : '';
                            $checkedShowRegion = in_array('region', $show) ? 'checked="checked"' : '';
                            $html .= '<div class="checkbox-list">
                                <h3>'.__('Display:', 'znajdz-prace-z-pracapl').'</h3>
                                <label><input type="checkbox" value="date" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowDate.'>'.__('job date', 'znajdz-prace-z-pracapl').'</label>
                                <label><input type="checkbox" value="company" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCompany.'>'.__('employer\'s name', 'znajdz-prace-z-pracapl').'</label>
                                <label><input type="checkbox" value="city" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCity.'>'.__('city', 'znajdz-prace-z-pracapl').'</label>
                                <label><input type="checkbox" value="region" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowRegion.'>'.__('region', 'znajdz-prace-z-pracapl').'</label>
                            </div>';

                            $html .= '<div>
                                <h3>'.__('Number of displayed jobs:', 'znajdz-prace-z-pracapl').'</h3>
                                <input type="text" value="'.$searchCount.'" name="znajdz-prace-z-pracapl[count]">
                            </div>';

                            $html .= wp_nonce_field(AdminSettings::SETTINGS_PAGE_NONCE, '_wpnonce', true, false);

                            $html .= '<div class="submit-box">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="'.__('Save settings', 'znajdz-prace-z-pracapl').'">
                            </div>';

                        $html .= '</form>
                    </div>
                    <div><p>' . __('Need technical support with plugin installation? Contact us at:', 'znajdz-prace-z-pracapl') . ' plugin-wp@praca.pl</p></div>
                </div>';

        return $html;
    }

	public function renderSettingsAppearanceForm(AppearanceSettings $options)
	{
		$titleColor = $options->isTitleColorEmpty() ? '' : $options->getTitleColor();
		$titleFontSize = $options->isTitleFontSizeEmpty() ? '' : $options->getTitleFontSize();
		$additionalInfoColor = $options->isAdditionalInfoColorEmpty() ? '' : $options->getAdditionalInfoColor();
		$additionalInfoFontSize = $options->isAdditionalInfoFontSizeEmpty() ? '' : $options->getAdditionalInfoFontSize();

        $html =
                '<div id="prPraca" class="plugin-settings">
                        <h2><div class="pr-icon32"> </div>'.__('Appearance', 'znajdz-prace-z-pracapl').'</h2>
                        <form method="post" action="#" id="widgetSettings">
                            <h3>' . __('Job offer title', 'znajdz-prace-z-pracapl') . '</h3>
                        	<table class="form-table" role="presentation">
	                            <tbody>
	                                <tr>
	                                    <th>' . __('Font size', 'znajdz-prace-z-pracapl') . '</th>
	                                    <td>
	                                    	<input
	                                    	 type="number"
	                                    	 id="titleFontSizeInput"
	                                    	 name="znzppl_appearance[titleFontSize]"
	                                    	 list="titleFontSizeList"
	                                    	 value="' . $titleFontSize . '"
	                                    	 max="90"
	                                    	 >
											    <datalist id="titleFontSizeList">
											      <option value="12">
											      <option value="18">
											      <option value="24">
											      <option value="28">
											      <option value="32">
											      <option value="36">
											    </datalist>px
											    <p><a id="resetTitleFontSize">' . __('Restore', 'znajdz-prace-z-pracapl') . '</a></p>
									    </td>
									</tr>
	                                <tr>
	                                    <th>' . __('Text color', 'znajdz-prace-z-pracapl') . '</th>
	                                    <td>
	                                        <input type="color" id="titleColorInput" name="znzppl_appearance[titleColor]" value="' . $titleColor . '"/>
	                                        <p><a id="resetTitleColor">' . __('Restore', 'znajdz-prace-z-pracapl') . '</a></p>
	                                    </td>
									</tr>
								</tbody>
                            </table>
                            <h3>' . __('Job offer additional info', 'znajdz-prace-z-pracapl') . '</h3>
                        	<table class="form-table" role="presentation">
	                            <tbody>
	                                <tr>
	                                    <th>' . __('Font size', 'znajdz-prace-z-pracapl') . '</th>
	                                    <td>
	                                    	<input
	                                    	 type="number"
	                                    	 id="additionalInfoFontSizeInput"
	                                    	 name="znzppl_appearance[additionalInfoFontSize]"
	                                    	 list="additionalInfoFontSizeList"
	                                    	 value="' . $additionalInfoFontSize . '"
	                                    	 max="90"
	                                    	 >
											    <datalist id="additionalInfoFontSizeList">
											      <option value="12">
											      <option value="18">
											      <option value="24">
											      <option value="28">
											      <option value="32">
											      <option value="36">
											    </datalist>px
											    <p><a id="resetAdditionalInfoFontSize">' . __('Restore', 'znajdz-prace-z-pracapl') . '</a></p>
									    </td>
									</tr>
	                                <tr>
	                                    <th>' . __('Text color', 'znajdz-prace-z-pracapl') . '</th>
	                                    <td>
	                                        <input type="color" id="additionalInfoColorInput" name="znzppl_appearance[additionalInfoColor]" value="' . $additionalInfoColor . '"/>
	                                        <p><a id="resetAdditionalInfoColor">' . __('Restore', 'znajdz-prace-z-pracapl') . '</a></p>
	                                    </td>
									</tr>
								</tbody>
                            </table>'
                            . wp_nonce_field(AdminSettings::APPEARANCE_SETTINGS_PAGE_NONCE, '_wpnonce', true, false) .                
                            '<div class="submit-box">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="'.__('Save settings', 'znajdz-prace-z-pracapl').'">
                            </div>
                        </form>
                    </div>
                ';

        return $html;
    }

    public function renderSidebarWidgetSettingsForm($options = array()) {

        if(!$options) $options = array();
        $show = array();

        if(!empty($options['show']) && is_array($options['show'])) {
            $show = array_filter($options['show'], 'htmlentities');
        }
        $searchCount = !empty($options['count']) ? (int) $options['count'] : self::OFFERS_COUNT;
        $prPracaSidebarWidgetTitle = !empty($options['sidebarWidgetTitle']) ? htmlentities($options['sidebarWidgetTitle']) : '';

        $html =
            '<div id="prPraca" class="widget-settings">
                <div id="prPracaWidgetSettings">';
                    $html .= '<div>
                        <div>'.__('Title:', 'znajdz-prace-z-pracapl').'</div>
                        <input type="text" value="'.$prPracaSidebarWidgetTitle.'" name="znajdz-prace-z-pracapl[sidebarWidgetTitle]">
                    </div>';
                    $checkedShowDate = in_array('date', $show) ? 'checked="checked"' : '';
                    $checkedShowCompany = in_array('company', $show) ? 'checked="checked"' : '';
                    $checkedShowCity = in_array('city', $show) ? 'checked="checked"' : '';
                    $checkedShowRegion = in_array('region', $show) ? 'checked="checked"' : '';
                    $html .= '<div class="checkbox-list">
                        <span>'.__('Display:', 'znajdz-prace-z-pracapl').'</span>
                        <label><input type="checkbox" value="date" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowDate.'>'.__('job date', 'znajdz-prace-z-pracapl').'</label>
                        <label><input type="checkbox" value="company" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCompany.'>'.__('employer\'s name', 'znajdz-prace-z-pracapl').'</label>
                        <label><input type="checkbox" value="city" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCity.'>'.__('city', 'znajdz-prace-z-pracapl').'</label>
                        <label><input type="checkbox" value="region" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowRegion.'>'.__('region', 'znajdz-prace-z-pracapl').'</label>
                    </div>';

                    $html .= wp_nonce_field('widget-settings-form', '_wpnonce', true, false);

                    $html .= '<div>
                        <span>'.__('Number of displayed jobs:', 'znajdz-prace-z-pracapl').'</span>
                        <input class="count" type="text" value="'.$searchCount.'" name="znajdz-prace-z-pracapl[count]">
                    </div>';
                $html .= '</div>
            </div>';

        return $html;
    }

    public function renderSidebarWidget($prAds, $prOptions, AppearanceSettings $appearanceSettings) {
	    $infoIconPath = zpzppl_get_asset_path(('/public/img/info.png'));

        $titleStyle = 'style="';
        if (!$appearanceSettings->isTitleColorEmpty()) {
            $titleStyle .= 'color: ' . $appearanceSettings->getTitleColor() . ';';
        }
        if (!$appearanceSettings->isTitleFontSizeEmpty()) {
            $titleStyle .= 'font-size: ' . $appearanceSettings->getTitleFontSize() . 'px;';
        }
        $titleStyle .= '"';

        $additionalInfoStyle = 'style="margin-left: 15px;margin-bottom:20px;';
        if (!$appearanceSettings->isAdditionalInfoColorEmpty()) {
            $additionalInfoStyle .= 'color: ' . $appearanceSettings->getAdditionalInfoColor() . ';';
        }
        if (!$appearanceSettings->isAdditionalInfoFontSizeEmpty()) {
            $additionalInfoStyle .= 'font-size: ' . $appearanceSettings->getAdditionalInfoFontSize() . 'px;';
        }
        $additionalInfoStyle .= '"';

        $showCompany = $showRegion = $showCity = $showDate = false;
        if(!empty($prOptions['show'])) {
            $showCompany = in_array('company', $prOptions['show']) ? true : false;
            $showCity = in_array('city', $prOptions['show']) ? true : false;
            $showRegion = in_array('region', $prOptions['show']) ? true : false;
            $showDate = in_array('date', $prOptions['show']) ? true : false;
        }

        $output = '<div id="prWidgetSitebar">';
            $output .= '<div class="zpzppl-offers-list-title">'.__('Job offers from service', 'znajdz-prace-z-pracapl').' <a href="https://www.praca.pl" title="Praca.pl">Praca.pl</a></div>';
            $output .= '<ul>';
                if(is_array($prAds) && count($prAds)) {
                    foreach($prAds as $ad) {
                        $output .= '<li>';
                            $output .= '<strong>
                                <a target="_blank" href="'.$ad['url'].'?rf=widget&utm_source=widget&utm_medium=plugin" ' . $titleStyle . '>'.$ad['title'].'</a>
                            </strong>';
                            $output .= '<div  ' . $additionalInfoStyle . ' >';
                                if($showCompany && !empty($ad['company'])) $output .= '<span>'.$ad['company'].'</span>, ';

                                if(($showRegion || $showCity) && !empty($ad['count']) && $ad['count'] > 1) {
                                    $output .= '<span>'.$ad['count'] . ' ' . __('regions', 'znajdz-prace-z-pracapl').'</span>, ';
                                } else {
                                    $place = array();
                                    if($showRegion && !empty($ad['region'])) $place[] = $ad['region'];
                                    if($showCity && !empty($ad['city'])) $place[] = $ad['city'];
                                    if(!empty($place)) $output .= '<span>'.implode(', ', $place).'</span>, ';
                                }

                                if($showDate) {
                                    $dateAd = new DateTime($ad['date']);
                                    $output .= '<span style="white-space:nowrap;">'.$dateAd->format('Y-m-d').'</span>, ';
                                }

                                $output = trim($output, ', ');
                            $output .= '</div>';
                        $output .= '</li>';
                    }
                } else {
                    $output .= '<li>'.__('There are no job offers', 'znajdz-prace-z-pracapl').'</li>';
                }
            $output .= '</ul>';
            $output .= '<div id="prWidgetSidebar-moreInfo">
                <a href="https://www.praca.pl/dodatki/plugin-wordpress.html"><img src="' . $infoIconPath . '" title="Oferty pracy z Praca.pl" alt="Portal Praca.pl" /></a>
            </div>';
        $output .= '</div>';

        return $output;
    }

    public function renderprPracaJobsShortCode($prAds, $prOptions)
    {
        $infoIconPath = zpzppl_get_asset_path(('/public/img/info.png'));

        $showCompany = $showRegion = $showCity = $showDate = false;
        if(!empty($prOptions['show'])) {
            $showCompany = in_array('company', $prOptions['show']) ? true : false;
            $showCity = in_array('city', $prOptions['show']) ? true : false;
            $showRegion = in_array('region', $prOptions['show']) ? true : false;
            $showDate = in_array('date', $prOptions['show']) ? true : false;
        }
        $mainClass = empty($prOptions['class']) ? '' : 'class="'.$prOptions['class'].'"';

        $output = '<div id="prPracaJobsShortcode" '.$mainClass.'>';
            $output .= '<div class="zpzppl-offers-list-title">'.__('Job offers from service', 'znajdz-prace-z-pracapl').' <a href="https://www.praca.pl" title="Praca.pl">Praca.pl</a></div>';
            if(is_array($prAds) && count($prAds)) {
                foreach($prAds as $ad) {
                    $output .= '<div>';
                        $output .= '<div><strong><a target="_blank" href="'.$ad['url']. '?rf=widget&utm_source=widget&utm_medium=plugin">'.$ad['title'].'</a></strong></div>';
                        $output .= '<div style="margin-left: 15px">';
                            if($showCompany && !empty($ad['company'])) $output .= '<span>'.$ad['company'].'</span>, ';

                            if(($showRegion || $showCity) && !empty($ad['count']) && $ad['count'] > 1) {
                                $output .= '<span>'.$ad['count'] . ' ' . __('regions', 'znajdz-prace-z-pracapl').'</span>, ';
                            } else {
                                $place = array();
                                if($showRegion && !empty($ad['region'])) $place[] = $ad['region'];
                                if($showCity && !empty($ad['city'])) $place[] = $ad['city'];
                                if(!empty($place)) $output .= '<span>'.implode(', ', $place).'</span>, ';
                            }

                            if($showDate) {
                                $dateAd = new DateTime($ad['date']);
                                $output .= '<span style="white-space:nowrap;">'.$dateAd->format('Y-m-d').'</span>, ';
                            }
                            $output = trim($output, ', ');
                        $output .= '</div>';
                    $output .= '</div>';
                }
            } else {
                $output .= '<div>'.__('There are no job offers', 'znajdz-prace-z-pracapl').'</div>';
            }
        $output .= '<div id="prWidgetSidebar-moreInfo">
                <a href="https://www.praca.pl/dodatki/plugin-wordpress.html"><img src="' . $infoIconPath . '" title="Oferty pracy z Praca.pl" alt="Portal Praca.pl" /></a>
            </div>';
        $output .= '</div>';

        return $output;
    }

    public function __call($method, $args) {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

}