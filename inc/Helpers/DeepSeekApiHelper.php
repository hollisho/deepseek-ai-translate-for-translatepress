<?php
namespace hollisho\translatepress\translate\deepseek\inc\Helpers;

use Exception;

class DeepSeekApiHelper {

    const supportedLanguages =  [
        'ar' => 'Arabic',
        'bg' => 'Bulgarian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'de' => 'German',
        'el' => 'Greek',
        'en' => 'English',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'hu' => 'Hungarian',
        'id' => 'Indonesian',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'lt' => 'Lithuanian',
        'lv' => 'Latvian',
        'nb' => 'Norwegian Bokmål',
        'nl' => 'Dutch',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sv' => 'Swedish',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'zh-cn' => 'Chinese (simplified)',
        'zh-tw' => 'Chinese (traditional)',

    ];

    /**
     * @param $texts
     * @param $sourceLang
     * @param $targetLang
     * @return string
     * @author Hollis
     *
     * $data = [
     * 'model' => 'deepseek-chat',
     * 'messages' => [
     *     ['role' => 'user', 'content' => $prompt]
     * ],
     * 'temperature' => 0.3,
     * 'max_tokens' => 4000  // 增加token限制以适应长文本
     * ];
     */
    public static function convert($texts, $sourceLang, $targetLang)
    {
        // 构造批量翻译提示词
        $counter = 0;
        $itemsList = implode("\n", array_map(function($text) use (&$counter) {
            return (++$counter) . ". " . $text;
        }, $texts));

        if ($sourceLang === 'auto') {
            $prompt = "请将以下内容逐条翻译成" . self::supportedLanguages[$targetLang] .
                "，保持专业语气，并严格按照原格式返回（保留编号）:\n\n" . $itemsList;
        } else {
            $prompt = "请将以下" . self::supportedLanguages[$sourceLang] . "内容逐条翻译成" .
                self::supportedLanguages[$targetLang] .
                "，保持专业语气，并严格按照原格式返回（保留编号）:\n\n" . $itemsList;
        }
        return $prompt;
    }



    public static function parseTranslatedItems($content, $expectedCount) {
        $lines = explode("\n", $content);
        $items = [];

        foreach ($lines as $line) {
            // 匹配 "数字. 翻译内容" 格式
            if (preg_match('/^\s*(\d+)\.\s*(.+?)\s*$/', $line, $matches)) {
                $index = (int)$matches[1] - 1;  // 转换为0-based索引
                $items[$index] = $matches[2];
            }
        }


        // 按索引排序
        ksort($items);
        return array_values($items);
    }

}


