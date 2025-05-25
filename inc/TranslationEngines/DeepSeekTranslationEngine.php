<?php
namespace hollisho\translatepress\translate\deepseek\inc\TranslationEngines;

use hollisho\helpers\ArrayHelper;
use hollisho\translatepress\translate\deepseek\inc\Helpers\DeepSeekApiHelper;
use TRP_Machine_Translator;
use WP_Error;

/**
 * @author Hollis
 * @desc deepseek machine translation engine
 * Class DeepSeekMachineTranslationEngine
 * @package hollisho\translatepress\translate\deepseek\inc\TranslationEngines
 */
class DeepSeekTranslationEngine extends TRP_Machine_Translator
{
    const ENGINE_KEY = 'deepseek_translate';

    const FIELD_API_KEY = 'deepseek-api-key';

    /**
     * Send request to Google Translation API
     *
     * @param string $source_language       Translate from language
     * @param string $language_code         Translate to language
     * @param array $strings_array          Array of string to translate
     *
     * @return array|WP_Error               Response
     */
    public function send_request( $source_language, $language_code, $strings_array)
    {
        /* build our translation request */
        $prompt = DeepSeekApiHelper::convert($strings_array, $source_language, $language_code);

        $data = [
            'model' => 'deepseek-chat',
            'temperature' => 0.3,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 4000  // 增加token限制以适应长文本
        ];

        $referer = $this->get_referer();

        /* Due to url length restrictions we need so send a POST request faked as a GET request and send the strings in the body of the request and not in the URL */
        return wp_remote_post( "{$this->get_api_url()}", array(
                'method'    => 'POST',
                'timeout'   => 45,
                'headers'   => [
                    'Referer'                => $referer,
                    'Authorization'          => ' Bearer ' . $this->get_api_key(),
                    'Content-Type'           => 'application/json'
                ],
                'body'      => json_encode($data),
            )
        );
    }

    public function translate_array($new_strings, $target_language_code, $source_language_code)
    {
        if ( $source_language_code == null )
            $source_language_code = $this->settings['default-language'];

        if( empty( $new_strings ) || !$this->verify_request_parameters( $target_language_code, $source_language_code ) )
            return [];

        $translated_strings = [];

        $source_language = apply_filters( 'trp_deepseek_source_language', $this->machine_translation_codes[$source_language_code], $source_language_code, $target_language_code );
        $target_language = apply_filters( 'trp_deepseek_target_language', $this->machine_translation_codes[$target_language_code], $source_language_code, $target_language_code );

        /* split our strings that need translation in chunks of maximum 128 strings because Google Translate has a limit of 128 strings */
        $new_strings_chunks = array_chunk( $new_strings, 64, true );
        /* if there are more than 128 strings we make multiple requests */
        foreach( $new_strings_chunks as $new_strings_chunk ){
            $response = $this->send_request( $source_language, $target_language, $new_strings_chunk );

            // this is run only if "Log machine translation queries." is set to Yes.
            $this->machine_translator_logger->log(array(
                'strings'   => serialize( $new_strings_chunk),
                'response'  => serialize( $response ),
                'lang_source'  => $source_language,
                'lang_target'  => $target_language,
            ));

            /* analyze the response */
            if ( is_array( $response ) && ! is_wp_error( $response ) && isset( $response['response'] ) &&
                isset( $response['response']['code']) && $response['response']['code'] == 200 ) {

                $this->machine_translator_logger->count_towards_quota( $new_strings_chunk );


                /**
                 *

                {
                    "id": "930c60df-bf64-41c9-a88e-3ec75f81e00e",
                    "choices": [
                        {
                            "finish_reason": "stop",
                            "index": 0,
                            "message": {
                                "content": "Hello! How can I help you today?",
                                "role": "assistant"
                            }
                        }
                    ],
                    "created": 1705651092,
                    "model": "deepseek-chat",
                    "object": "chat.completion",
                    "usage": {
                        "completion_tokens": 10,
                        "prompt_tokens": 16,
                        "total_tokens": 26
                    }
                }
                 */
                $translation_response = json_decode( $response['body'] );

                if ( empty( $translation_response->error ) ) {

                    /* if we have strings build the translation strings array and make sure we keep the original keys from $new_string */
                    $translatedContent = ArrayHelper::getValue($translation_response, 'choices.0.message.content', []);
                    $translations = DeepSeekApiHelper::parseTranslatedItems($translatedContent, count($new_strings_chunk));
                    $i = 0;

                    foreach ( $new_strings_chunk as $key => $old_string ) {

                        if ( isset( $translations[ $i ] ) && !empty( $translations[ $i ] ) ) {
                            $translated_strings[ $key ] = $translations[ $i ];
                        } else {
                            $translated_strings[ $key ] = $old_string;
                        }

                        $i++;

                    }

                }

                if( $this->machine_translator_logger->quota_exceeded() )
                    break;

            }
        }

        // will have the same indexes as $new_string or it will be an empty array if something went wrong
        return $translated_strings;

    }


    /**
     * @return array|void|WP_Error
     * @author Hollis
     * @desc
     */
    public function test_request()
    {

        return $this->send_request( 'en', 'es', [ 'Where are you from ?', 'I Love you !' ] );

    }

    public function check_api_key_validity()
    {

        $machine_translator = $this;
        $translation_engine = $this->settings['trp_machine_translation_settings']['translation-engine'];
        $api_key            = $machine_translator->get_api_key();

        $is_error       = false;
        $return_message = '';

        if ( DeepSeekTranslationEngine::ENGINE_KEY === $translation_engine
            && $this->settings['trp_machine_translation_settings']['machine-translation'] === 'yes') {

            if ( isset( $this->correct_api_key ) && $this->correct_api_key != null ) {
                return $this->correct_api_key;
            }

            if ( empty( $api_key ) ) {
                $is_error       = true;
                $return_message = '请输入您的 API 密钥。格式请参考下面说明';
            }
            $this->correct_api_key = array(
                'message' => $return_message,
                'error'   => $is_error,
            );
        }

        return array(
            'message' => $return_message,
            'error'   => $is_error,
        );
    }

    public function get_api_key()
    {
        return isset( $this->settings['trp_machine_translation_settings'], $this->settings['trp_machine_translation_settings'][DeepSeekTranslationEngine::FIELD_API_KEY] )
            ? $this->settings['trp_machine_translation_settings'][DeepSeekTranslationEngine::FIELD_API_KEY] : false;
    }

    public function get_api_url()
    {
        return 'https://api.deepseek.com/chat/completions';
    }

}