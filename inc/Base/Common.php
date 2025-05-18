<?php

namespace hollisho\translatepress\translate\deepseek\inc\Base;

class Common
{
    const HO_PLUGIN_VERSION = '1.0.0';

    const HO_PLUGIN_ID = 'deepseek-ai-translate-for-translatepress';

    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public function __construct() {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/' . self::HO_PLUGIN_ID . '.php';
    }
}