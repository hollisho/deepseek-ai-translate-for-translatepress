<?php

namespace hollisho\translatepress\translate\deepseek\inc\ServiceProvider;

use hollisho\translatepress\translate\deepseek\inc\Base\Common;
use hollisho\translatepress\translate\deepseek\inc\Base\ServiceProviderInterface;

class RegisterScripts extends Common implements ServiceProviderInterface
{

    public function register()
    {
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );
    }

    function admin_enqueue_scripts($hook)
    {
        if ($hook == 'admin_page_trp_machine_translation') {
            wp_enqueue_script('trp-settings-script-deepseek',
            $this->plugin_url . 'assets/js/trp-back-end-script-deepseek.js',
            ['trp-settings-script'], Common::HO_PLUGIN_VERSION, true);
        }
        
    }
}