<?php

require_once 'iView.php';
require_once 'View/Country.php';
require_once 'View/JobCategory.php';
require_once 'View/Regions.php';

class PrPracaWpView implements PrPracaViewInterface {

    const OFFERS_COUNT = 10;

    public $_;

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
                        <h2><div class="pr-icon32"> </div>'.$this->_('Settings').'</h2>
                        <p class="prPracaInfoBox">'.$this->_('To display the plugin on your website, go to the "Appearance" bookmark and then click on "Widgets".  Activate the "Jobs from Praca.pl" wigdet, for example by dragging and dropping it in the Sidebar.').'</p>
                        <form method="post" action="#" id="widgetSettings">
                            <input type="hidden" value="'.$prPracaSidebarWidgetTitle.'" name="znajdz-prace-z-pracapl[sidebarWidgetTitle]">';
                            $html .= '<div>
                                <h3>'.$this->_('Search params').'</h3>

                                    <h4>'.$this->_('Phrase (optional):').'</h4>
                                    <label><input type="text" value="'.$searchPhrase.'" name="znajdz-prace-z-pracapl[phrase]"></label>
                                    <div>'.$this->_('Position, company, keyword.').'</div>

                                    <h4>'.$this->_('City (optional):').'</h4>
                                    <label><input type="text" value="'.$searchCity.'" name="znajdz-prace-z-pracapl[city]"></label>
                                    <div>'.$this->_('Provide the name of the city.').'</div>

                                    <h4>'.$this->_('Company ID (optional):').'</h4>
                                    <label><input type="text" value="'.$searchCompany.'" name="znajdz-prace-z-pracapl[company]"></label>
                                    <div>'.$this->_('Current job offers from the specified employer on Praca.pl will be displayed.').'</div>
                                    <div>'.$this->_('You can provide several company IDs, separating them with a semicolon, e.g. 1234;1234').'</div>

                                    <h4>'.$this->_('Company ID to exclude (optional):').'</h4>
                                    <label><input type="text" value="'.$searchECompany.'" name="znajdz-prace-z-pracapl[ecompany]"></label>
                                    <div>'.$this->_('Job offers from the specified employer on Praca.pl will not be displayed.').'</div>
                                    <div>'.$this->_('You can provide several company IDs, separating them with a semicolon, e.g. 1234;1234').'</div>
                                </div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Category (optional):').'</h3>';
                                foreach(PrPracaViewJobCategory::$ITEMS as $jcKey => $jcValue) {
                                    $checked = in_array($jcKey, $jobCategory) ? 'checked="checked"' : '';
                                    $html .= '<label><input type="checkbox" value="'.$jcKey.'" name="znajdz-prace-z-pracapl[jobcategory][]" '.$checked.'>'.$this->_($jcValue).'</label>';
                                }
                            $html .= '</div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Region (optional):').'</h3>';
                                $html .= '<div class="pr-region-main">';
                                    foreach(PrPracaViewRegions::$ITEMS as $rKey => $rValue) {
                                        if($rKey > 16 || $rKey == 0) {
                                            $checked = in_array($rKey, $regions) ? 'checked="checked"' : '';
                                            $html .= '<label><input type="checkbox" value="'.$rKey.'" name="znajdz-prace-z-pracapl[region][]" '.$checked.'>'.$this->_($rValue).'</label>';
                                        }
                                    }
                                $html .= '</div>';
                                $html .= '<div class="pr-region-details">';
                                    foreach(PrPracaViewRegions::$ITEMS as $rKey => $rValue) {
                                        if($rKey > 0 && $rKey <= 16) {
                                            $checked = in_array($rKey, $regions) ? 'checked="checked"' : '';
                                            $html .= '<label><input type="checkbox" value="'.$rKey.'" name="znajdz-prace-z-pracapl[region][]" '.$checked.'>'.$this->_($rValue).'</label>';
                                        }
                                    }
                                $html .= '</div>';
                            $html .= '</div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Country (optional):').'</h3>';
                                foreach(PrPracaViewCountry::$ITEMS as $cKey => $cValue) {
                                    $checked = in_array($cKey, $country) ? 'checked="checked"' : '';
                                    $html .= '<label><input type="checkbox" value="'.$cKey.'" name="znajdz-prace-z-pracapl[country][]" '.$checked.'>'.$this->_($cValue).'</label>';
                                }
                            $html .= '</div>';

                            $checkedShowDate = in_array('date', $show) ? 'checked="checked"' : '';
                            $checkedShowCompany = in_array('company', $show) ? 'checked="checked"' : '';
                            $checkedShowCity = in_array('city', $show) ? 'checked="checked"' : '';
                            $checkedShowRegion = in_array('region', $show) ? 'checked="checked"' : '';
                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Display:').'</h3>
                                <label><input type="checkbox" value="date" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowDate.'>'.$this->_('job date').'</label>
                                <label><input type="checkbox" value="company" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCompany.'>'.$this->_('employer\'s name').'</label>
                                <label><input type="checkbox" value="city" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCity.'>'.$this->_('city').'</label>
                                <label><input type="checkbox" value="region" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowRegion.'>'.$this->_('region').'</label>
                            </div>';

                            $html .= '<div>
                                <h3>'.$this->_('Number of displayed jobs:').'</h3>
                                <input type="text" value="'.$searchCount.'" name="znajdz-prace-z-pracapl[count]">
                            </div>';

						    $showCredentials = false;
						    if(isset($options['showCredentials'])) {
							    $showCredentials = (bool) $options['showCredentials'];
						    }

	                        $showCredentialsChecked = $showCredentials ? 'checked="checked"' : '';
                            $html .= '<div>
                                <h3>'.$this->_('Show credentials:').' <input type="checkbox" name="znajdz-prace-z-pracapl[showCredentials]" ' . $showCredentialsChecked . '></h3>
                               
                            </div>';

                            $html .= '<div class="submit-box">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="'.$this->_('Save settings').'">
                            </div>';

                        $html .= '</form>
                    </div>
                    <div><p>' . $this->_('Need technical support with plugin installation? Contact us at:') . ' plugin-wp@praca.pl</p></div>
                </div>';

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
                        <div>'.$this->_('Title:').'</div>
                        <input type="text" value="'.$prPracaSidebarWidgetTitle.'" name="znajdz-prace-z-pracapl[sidebarWidgetTitle]">
                    </div>';
                    $checkedShowDate = in_array('date', $show) ? 'checked="checked"' : '';
                    $checkedShowCompany = in_array('company', $show) ? 'checked="checked"' : '';
                    $checkedShowCity = in_array('city', $show) ? 'checked="checked"' : '';
                    $checkedShowRegion = in_array('region', $show) ? 'checked="checked"' : '';
                    $html .= '<div class="checkbox-list">
                        <span>'.$this->_('Display:').'</span>
                        <label><input type="checkbox" value="date" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowDate.'>'.$this->_('job date').'</label>
                        <label><input type="checkbox" value="company" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCompany.'>'.$this->_('employer\'s name').'</label>
                        <label><input type="checkbox" value="city" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowCity.'>'.$this->_('city').'</label>
                        <label><input type="checkbox" value="region" name="znajdz-prace-z-pracapl[show][]" '.$checkedShowRegion.'>'.$this->_('region').'</label>
                    </div>';

                    $html .= '<div>
                        <span>'.$this->_('Number of displayed jobs:').'</span>
                        <input class="count" type="text" value="'.$searchCount.'" name="znajdz-prace-z-pracapl[count]">
                    </div>';
                $html .= '</div>
            </div>';

        return $html;
    }

    public function renderSidebarWidget($prAds, $prOptions) {
	    $infoIconPath = plugins_url('/public/img/info.png', __DIR__ . '/../znajdz-prace-z-pracapl.php');

        $showCompany = $showRegion = $showCity = $showDate = false;
        if(!empty($prOptions['show'])) {
            $showCompany = in_array('company', $prOptions['show']) ? true : false;
            $showCity = in_array('city', $prOptions['show']) ? true : false;
            $showRegion = in_array('region', $prOptions['show']) ? true : false;
            $showDate = in_array('date', $prOptions['show']) ? true : false;
        }

	    $showCredentials = false;
	    if(isset($prOptions['showCredentials'])) {
		    $showCredentials = (bool) $prOptions['showCredentials'];
	    }

        $output = '<div id="prWidgetSitebar">';
			if ($showCredentials) {
				$output .= '<div>'.$this->_('Job offers from service').' <a href="https://www.praca.pl" title="Praca.pl">Praca.pl</a></div>';
			}
            $output .= '<ul>';
                if(is_array($prAds) && count($prAds)) {
                    foreach($prAds as $ad) {
                        $output .= '<li>';
                            $output .= '<strong><a href="'.$ad['url'].'?rf=widget&utm_source=widget&utm_medium=plugin">'.$ad['title'].'</a></strong>';
                            $output .= '<div style="margin-left: 15px">';
                                if($showCompany && !empty($ad['company'])) $output .= '<span>'.$ad['company'].'</span>, ';

                                if(($showRegion || $showCity) && !empty($ad['count']) && $ad['count'] > 1) {
                                    $output .= '<span>'.$ad['count'] . ' ' . $this->_('regions').'</span>, ';
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

                                $output = substr(trim($output),0,-1);
                            $output .= '</div>';
                        $output .= '</li>';
                    }
                } else {
                    $output .= '<li>'.$this->_('There are no job offers').'</li>';
                }
            $output .= '</ul>';
			if ($showCredentials) {
				$output .= '<div id="prWidgetSidebar-moreInfo">
					<a href="https://www.praca.pl/dodatki/plugin-wordpress.html"><img src="' . $infoIconPath . '" title="Oferty pracy z Praca.pl" alt="Portal Praca.pl" /></a>
				</div>';
			}
        $output .= '</div>';

        return $output;
    }

    public function renderprPracaJobsShortCode($prAds, $prOptions) {

        $showCompany = $showRegion = $showCity = $showDate = false;
        if(!empty($prOptions['show'])) {
            $showCompany = in_array('company', $prOptions['show']) ? true : false;
            $showCity = in_array('city', $prOptions['show']) ? true : false;
            $showRegion = in_array('region', $prOptions['show']) ? true : false;
            $showDate = in_array('date', $prOptions['show']) ? true : false;
        }
        $mainClass = empty($prOptions['class']) ? '' : 'class="'.$prOptions['class'].'"';

	    $showCredentials = false;
	    if(isset($prOptions['showCredentials'])) {
		    $showCredentials = (bool) $prOptions['showCredentials'];
	    }

        $output = '<div id="prPracaJobsShortcode" '.$mainClass.'>';
			if ($showCredentials) {
				$output .= '<div>'.$this->_('Job offers from service').' <a href="https://www.praca.pl" title="Praca.pl">Praca.pl</a></div>';
			}
            if(is_array($prAds) && count($prAds)) {
                foreach($prAds as $ad) {
                    $output .= '<div>';
                        $output .= '<div><strong><a href="'.$ad['url'].'">'.$ad['title'].'</a></strong></div>';
                        $output .= '<div style="margin-left: 15px">';
                            if($showCompany && !empty($ad['company'])) $output .= '<span>'.$ad['company'].'</span>, ';

                            if(($showRegion || $showCity) && !empty($ad['count']) && $ad['count'] > 1) {
                                $output .= '<span>'.$ad['count'] . ' ' . $this->_('regions').'</span>, ';
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
                            $output = substr(trim($output),0,-1);
                        $output .= '</div>';
                    $output .= '</div>';
                }
            } else {
                $output .= '<div>'.$this->_('There are no job offers').'</div>';
            }
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