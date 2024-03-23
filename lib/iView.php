<?php

interface PrPracaViewInterface {
    public function renderSettingsForm($options = array());
    public function renderSidebarWidgetSettingsForm($options = array());
    public function renderprPracaJobsShortCode($prAds, $prOptions);
}