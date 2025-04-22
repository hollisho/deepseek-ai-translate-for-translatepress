<?php
namespace hollisho\translatepress\translate\deepseek\inc\ServiceProvider;

use hollisho\translatepress\translate\deepseek\inc\Base\ServiceProviderInterface;
use hollisho\translatepress\translate\deepseek\inc\TranslationEngines\YouDaoMachineTranslationEngine;
use TRP_Translate_Press;

/**
 * @author Hollis
 * @desc yodao machine translation engine service provider
 * Class TranslatePressMachineTranslationEngines
 * @package hollisho\translatepress\translate\deepseek\inc\ServiceProvider
 */
class RegisterMachineTranslationEngines implements ServiceProviderInterface
{

    public function register()
    {
        add_filter( 'trp_machine_translation_engines', [$this, 'add_engine'], 20 );
        add_filter( 'trp_automatic_translation_engines_classes', [$this, 'add_engine_classes'], 20, 1 );
        add_action( 'trp_machine_translation_extra_settings_middle', [$this, 'add_settings'], 20, 1  );
        add_action( 'trp_machine_translation_sanitize_settings', [$this, 'sanitize_settings'], 20, 2 );


        add_filter( 'trp_deepseek_target_language', [$this, 'configure_api_target_language'], 20, 3 );
        add_filter( 'trp_deepseek_source_language', [$this, 'configure_api_source_language'], 20, 3 );

    }

    public function add_engine_classes( $classes ){
        $classes[YouDaoMachineTranslationEngine::ENGINE_KEY] = YouDaoMachineTranslationEngine::class;
        return $classes;
    }

    public function add_engine( $engines ){
        $engines[] = [ 
            'value' => YouDaoMachineTranslationEngine::ENGINE_KEY, 
            'label' => esc_html(__('YouDao', 'ho-deepseek-translate-for-translatepress')), // translators: translation engine name
        ];

        return $engines;
    }

    public function add_settings( $settings ){
        $trp                = TRP_Translate_Press::get_trp_instance();
        $machine_translator = $trp->get_component( 'machine_translator' );

        // Error messages.
        $show_errors   = false;
        $error_message = '';

        $translation_engine = $settings['translation-engine'] ?? '';

        // Check for API errors.
        if ( YouDaoMachineTranslationEngine::ENGINE_KEY === $translation_engine ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            $machine_translator = $trp->get_component( 'machine_translator' );
            $api_check = $machine_translator->check_api_key_validity();
        }

        if ( isset($api_check) && true === $api_check['error'] ) {
            $error_message = $api_check['message'];
            $show_errors    = true;
        }

        $text_input_classes = array(
            'trp-text-input',
        );
        if ( $show_errors && YouDaoMachineTranslationEngine::ENGINE_KEY === $translation_engine ) {
            $text_input_classes[] = 'trp-text-input-error';
        }

        ?>

        <tr>
            <th scope="row">
                <?php 
                    // translators: input api key
                    echo esc_html(__('deepseek api key', 'ho-deepseek-translate-for-translatepress')); 
                ?>
            </th>
            <td>
                <?php
                // Display an error message above the input.
                if ( $show_errors && YouDaoMachineTranslationEngine::ENGINE_KEY === $translation_engine ) {
                    ?>
                    <p class="trp-error-inline">
                        <?php echo wp_kses_post( $error_message ); ?>
                    </p>
                    <?php
                }
                ?>
                <input type="text" id="trp-deepseek-api-key" class="<?php echo esc_html( implode( ' ', $text_input_classes ) ); ?>"
                       name="trp_machine_translation_settings[<?php echo esc_attr(YouDaoMachineTranslationEngine::FIELD_API_KEY) ?>]"
                       value="<?php if( !empty( $settings[YouDaoMachineTranslationEngine::FIELD_API_KEY] ) ) echo esc_attr( $settings[YouDaoMachineTranslationEngine::FIELD_API_KEY]); ?>"/>
                <?php
                // Show error or success SVG.
                if ( method_exists( $machine_translator, 'automatic_translation_svg_output' ) && YouDaoMachineTranslationEngine::ENGINE_KEY === $translation_engine ) {
                    $machine_translator->automatic_translation_svg_output( $show_errors );
                }
                ?>
                <p class="description">
                    <?php 
                        // translators: deepseek api key
                        echo esc_html(__('key format: app id#app secret#VOCABID(optional)', 'ho-deepseek-translate-for-translatepress')); 
                    ?>
                </p>
                <p class="description">
                    <?php 
                        // translators: visit deepseek api url.
                        $text = __( 'Visit <a href="%s" target="_blank">this link</a> to see how you can set up an API key and control API costs.', 'ho-deepseek-translate-for-translatepress' );
                        echo wp_kses( sprintf( $text, 'https://ai.deepseek.com/DOCSIRMA/html/trans/api/plwbfy/index.html' ), [ 'a' => [ 'href' => [], 'target'=> [] ] ] ) 
                    ?>
                </p>
            </td>

        </tr>

        <?php
    }

    public function sanitize_settings( $settings, $mt_settings ){
        if( !empty( $mt_settings[YouDaoMachineTranslationEngine::FIELD_API_KEY] ) )
            $settings[YouDaoMachineTranslationEngine::FIELD_API_KEY] = sanitize_text_field( $mt_settings[YouDaoMachineTranslationEngine::FIELD_API_KEY] );

        return $settings;
    }

    /**
     * Particularities for source language in API.
     *
     * PT_BR is not treated in the same way as for the target language
     *
     * @param $source_language
     * @param $source_language_code
     * @param $target_language_code
     * @return string
     */
    public function configure_api_source_language($source_language, $source_language_code, $target_language_code ){
        $exceptions_source_mapping_codes = array(
            'zh_HK' => 'zh-CHT',
            'zh_TW' => 'zh-CHT',
            'zh_CN' => 'zh-CHS',
            'en_GB' => 'en',
            'en_US' => 'en',
            'en_CA' => 'en',
            'en_ZA' => 'en',
            'en_NZ' => 'en',
            'en_AU' => 'en',
        );
        if ( isset( $exceptions_source_mapping_codes[$source_language_code] ) ){
            $source_language = $exceptions_source_mapping_codes[$source_language_code];
        }

        return $source_language;
    }

    /**
     * Particularities for target language in API
     *
     * @param $target_language
     * @param $source_language_code
     * @param $target_language_code
     * @return string
     */
    public function configure_api_target_language($target_language, $source_language_code, $target_language_code ){
        $exceptions_target_mapping_codes = array(
            'zh_HK' => 'zh-CHT',
            'zh_TW' => 'zh-CHT',
            'zh_CN' => 'zh-CHS',
            'en_GB' => 'en',
            'en_US' => 'en',
            'en_CA' => 'en',
            'en_ZA' => 'en',
            'en_NZ' => 'en',
            'en_AU' => 'en',
        );
        if ( isset( $exceptions_target_mapping_codes[$target_language_code] ) ){
            $target_language = $exceptions_target_mapping_codes[$target_language_code];
        }

        return $target_language;
    }
}