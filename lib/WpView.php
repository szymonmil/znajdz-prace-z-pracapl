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
                            <input type="hidden" value="'.$prPracaSidebarWidgetTitle.'" name="prPraca[sidebarWidgetTitle]">';
                            $html .= '<div>
                                <h3>'.$this->_('Search params').'</h3>

                                    <h4>'.$this->_('Phrase:').'</h4>
                                    <label><input type="text" value="'.$searchPhrase.'" name="prPraca[phrase]"></label>

                                    <h4>'.$this->_('City:').'</h4>
                                    <label><input type="text" value="'.$searchCity.'" name="prPraca[city]"></label>

                                    <h4>'.$this->_('Company ID from site Praca.pl:').'</h4>
                                    <label><input type="text" value="'.$searchCompany.'" name="prPraca[company]"></label>
                                    <div>'.$this->_('You can enter multiple values ​​separated by semicolons').'</div>

                                    <h4>'.$this->_('Excluded Company ID from site Praca.pl:').'</h4>
                                    <label><input type="text" value="'.$searchECompany.'" name="prPraca[ecompany]"></label>
                                    <div>'.$this->_('You can enter multiple values ​​separated by semicolons').'</div>
                                </div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Category:').'</h3>';
                                foreach(PrPracaViewJobCategory::$ITEMS as $jcKey => $jcValue) {
                                    $checked = in_array($jcKey, $jobCategory) ? 'checked="checked"' : '';
                                    $html .= '<label><input type="checkbox" value="'.$jcKey.'" name="prPraca[jobcategory][]" '.$checked.'>'.$this->_($jcValue).'</label>';
                                }
                            $html .= '</div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Region:').'</h3>';
                                $html .= '<div class="pr-region-main">';
                                    foreach(PrPracaViewRegions::$ITEMS as $rKey => $rValue) {
                                        if($rKey > 16 || $rKey == 0) {
                                            $checked = in_array($rKey, $regions) ? 'checked="checked"' : '';
                                            $html .= '<label><input type="checkbox" value="'.$rKey.'" name="prPraca[region][]" '.$checked.'>'.$this->_($rValue).'</label>';
                                        }
                                    }
                                $html .= '</div>';
                                $html .= '<div class="pr-region-details">';
                                    foreach(PrPracaViewRegions::$ITEMS as $rKey => $rValue) {
                                        if($rKey > 0 && $rKey <= 16) {
                                            $checked = in_array($rKey, $regions) ? 'checked="checked"' : '';
                                            $html .= '<label><input type="checkbox" value="'.$rKey.'" name="prPraca[region][]" '.$checked.'>'.$this->_($rValue).'</label>';
                                        }
                                    }
                                $html .= '</div>';
                            $html .= '</div>';

                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Country:').'</h3>';
                                foreach(PrPracaViewCountry::$ITEMS as $cKey => $cValue) {
                                    $checked = in_array($cKey, $country) ? 'checked="checked"' : '';
                                    $html .= '<label><input type="checkbox" value="'.$cKey.'" name="prPraca[country][]" '.$checked.'>'.$this->_($cValue).'</label>';
                                }
                            $html .= '</div>';

                            $checkedShowDate = in_array('date', $show) ? 'checked="checked"' : '';
                            $checkedShowCompany = in_array('company', $show) ? 'checked="checked"' : '';
                            $checkedShowCity = in_array('city', $show) ? 'checked="checked"' : '';
                            $checkedShowRegion = in_array('region', $show) ? 'checked="checked"' : '';
                            $html .= '<div class="checkbox-list">
                                <h3>'.$this->_('Display:').'</h3>
                                <label><input type="checkbox" value="date" name="prPraca[show][]" '.$checkedShowDate.'>'.$this->_('job date').'</label>
                                <label><input type="checkbox" value="company" name="prPraca[show][]" '.$checkedShowCompany.'>'.$this->_('employer\'s name').'</label>
                                <label><input type="checkbox" value="city" name="prPraca[show][]" '.$checkedShowCity.'>'.$this->_('city').'</label>
                                <label><input type="checkbox" value="region" name="prPraca[show][]" '.$checkedShowRegion.'>'.$this->_('region').'</label>
                            </div>';

                            $html .= '<div>
                                <h3>'.$this->_('Number of displayed jobs:').'</h3>
                                <input type="text" value="'.$searchCount.'" name="prPraca[count]">
                            </div>';

                            $html .= '<div class="submit-box">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="'.$this->_('Save settings').'">
                            </div>';

                        $html .= '</form>
                    </div>
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
                        <input type="text" value="'.$prPracaSidebarWidgetTitle.'" name="prPraca[sidebarWidgetTitle]">
                    </div>';
                    $checkedShowDate = in_array('date', $show) ? 'checked="checked"' : '';
                    $checkedShowCompany = in_array('company', $show) ? 'checked="checked"' : '';
                    $checkedShowCity = in_array('city', $show) ? 'checked="checked"' : '';
                    $checkedShowRegion = in_array('region', $show) ? 'checked="checked"' : '';
                    $html .= '<div class="checkbox-list">
                        <span>'.$this->_('Display:').'</span>
                        <label><input type="checkbox" value="date" name="prPraca[show][]" '.$checkedShowDate.'>'.$this->_('job date').'</label>
                        <label><input type="checkbox" value="company" name="prPraca[show][]" '.$checkedShowCompany.'>'.$this->_('employer\'s name').'</label>
                        <label><input type="checkbox" value="city" name="prPraca[show][]" '.$checkedShowCity.'>'.$this->_('city').'</label>
                        <label><input type="checkbox" value="region" name="prPraca[show][]" '.$checkedShowRegion.'>'.$this->_('region').'</label>
                    </div>';

                    $html .= '<div>
                        <span>'.$this->_('Number of displayed jobs:').'</span>
                        <input class="count" type="text" value="'.$searchCount.'" name="prPraca[count]">
                    </div>';
                $html .= '</div>
            </div>';

        return $html;
    }

    public function renderSidebarWidget($prAds, $prOptions) {

        $showCompany = $showRegion = $showCity = $showDate = false;
        if(!empty($prOptions['show'])) {
            $showCompany = in_array('company', $prOptions['show']) ? true : false;
            $showCity = in_array('city', $prOptions['show']) ? true : false;
            $showRegion = in_array('region', $prOptions['show']) ? true : false;
            $showDate = in_array('date', $prOptions['show']) ? true : false;
        }

        $output = '<div id="prWidgetSitebar">';
            $output .= '<div>'.$this->_('Offers come from service').' <a href="http://www.praca.pl">Praca.pl</a></div>';
            $output .= '<ul>';
                if(is_array($prAds) && count($prAds)) {
                    foreach($prAds as $ad) {
                        $output .= '<li>';
                            $output .= '<strong><a href="'.$ad['url'].'">'.$ad['title'].'</a></strong>';
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

        $output = '<div id="prPracaJobsShortcode" '.$mainClass.'>';
            $output .= '<div>'.$this->_('Offers come from service').' <a href="http://www.praca.pl">Praca.pl</a></div>';
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