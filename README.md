# DeepSeek AI Translation for TranslatePress

A lightweight WordPress plugin that integrates DeepSeek AI's advanced translation API with TranslatePress, providing high-quality machine translation for your multilingual WordPress website.

## Features

- **AI-Powered Translation**: Leverages DeepSeek's advanced AI model for accurate and contextual translations
- **Seamless Integration**: Works directly with TranslatePress without any complex setup
- **Batch Translation**: Efficiently processes multiple strings in a single API request
- **30+ Languages Support**: Supports over 30 languages including major world languages
- **Professional Quality**: Maintains professional tone and formatting in translations
- **Cost-Effective**: Uses DeepSeek's competitive pricing model

## Requirements

- WordPress 6.0 or higher
- PHP 7.2 or higher
- TranslatePress plugin (free or premium version)
- DeepSeek API key

## Installation

### Method 1: WordPress Admin Dashboard

1. Download the plugin ZIP file
2. Go to your WordPress admin dashboard
3. Navigate to **Plugins > Add New**
4. Click **Upload Plugin** and select the ZIP file
5. Click **Install Now** and then **Activate**

### Method 2: Manual Installation

1. Download and extract the plugin files
2. Upload the `deepseek-ai-translate-for-translatepress` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the **Plugins** menu in WordPress

## Configuration

### Step 1: Get Your DeepSeek API Key

1. Visit [DeepSeek Platform](https://platform.deepseek.com/)
2. Sign up for an account or log in
3. Navigate to the API section
4. Generate a new API key
5. Copy the API key for use in the plugin

### Step 2: Configure TranslatePress

1. Go to **Settings > TranslatePress**
2. Navigate to the **Automatic Translation** tab
3. Enable **Automatic Translation**
4. Select **DeepSeek Translate** as your translation engine
5. Enter your DeepSeek API key in the **DeepSeek API Key** field
6. Save the settings

## Usage

### Automatic Translation

Once configured, the plugin will automatically translate your content when:

- You switch to a different language using the language switcher
- New content is detected that hasn't been translated yet
- You manually trigger translation through TranslatePress interface

### Manual Translation

1. Navigate to any page on your website
2. Use the TranslatePress visual editor (pencil icon in the top bar)
3. Switch to your target language
4. Click on any text element to translate it
5. The plugin will automatically suggest translations using DeepSeek AI

### Supported Languages

The plugin supports translation between the following languages:

- Arabic (ar)
- Bulgarian (bg)
- Czech (cs)
- Danish (da)
- German (de)
- Greek (el)
- English (en)
- Spanish (es)
- Estonian (et)
- Finnish (fi)
- French (fr)
- Hungarian (hu)
- Indonesian (id)
- Italian (it)
- Japanese (ja)
- Korean (ko)
- Lithuanian (lt)
- Latvian (lv)
- Norwegian Bokmål (nb)
- Dutch (nl)
- Polish (pl)
- Portuguese (pt)
- Romanian (ro)
- Russian (ru)
- Slovak (sk)
- Slovenian (sl)
- Swedish (sv)
- Turkish (tr)
- Ukrainian (uk)
- Chinese Simplified (zh-cn)
- Chinese Traditional (zh-tw)

## Troubleshooting

### Common Issues

**Translation not working:**
- Verify your API key is correct and active
- Check if you have sufficient API credits
- Ensure TranslatePress is properly configured
- Check WordPress error logs for detailed error messages

**API Key validation failed:**
- Double-check your API key format
- Ensure the API key has translation permissions
- Verify your DeepSeek account is in good standing

**Slow translation speed:**
- This is normal for AI-powered translation
- Consider translating content in smaller batches
- Check your server's internet connection speed

## Service Reliability Notice

**Important:** DeepSeek's translation service may occasionally experience instability or downtime, which could result in translation failures. This is beyond our control as it depends on DeepSeek's infrastructure.

### Alternative Solutions

If you experience frequent service interruptions or need more reliable translation services, you can extend this plugin to integrate with other translation providers:

#### Recommended Alternative Services:

1. **Google Translate API**
   - High reliability and uptime
   - Extensive language support
   - Well-documented API

2. **Microsoft Translator**
   - Enterprise-grade reliability
   - Good pricing model
   - Strong neural translation quality

3. **Amazon Translate**
   - AWS infrastructure reliability
   - Pay-as-you-go pricing
   - Good integration with other AWS services

4. **OpenAI GPT API**
   - High-quality contextual translation
   - Good for creative and marketing content
   - More expensive but higher quality

#### Extending the Plugin

To integrate additional translation services:

1. **Create a new translation engine class** in `/inc/TranslationEngines/`
2. **Extend the base `TRP_Machine_Translator` class**
3. **Implement required methods:**
   - `send_request()` - Handle API communication
   - `translate_array()` - Process translation requests
   - `get_api_key()` - Retrieve API credentials
   - `check_api_key_validity()` - Validate API key

4. **Register your new engine** in `/inc/ServiceProvider/RegisterMachineTranslationEngines.php`

5. **Add configuration fields** for the new service's API settings

Example structure for a new translation engine:

```php
class CustomTranslationEngine extends TRP_Machine_Translator {
    const ENGINE_KEY = 'custom_translate';
    
    public function send_request($source_language, $language_code, $strings_array) {
        // Implement API call to your chosen service
    }
    
    public function translate_array($new_strings, $target_language_code, $source_language_code) {
        // Process and return translated strings
    }
    
    // ... other required methods
}
```

This modular approach allows you to maintain multiple translation providers and switch between them based on availability and requirements.

## Support

For support and bug reports:

- **GitHub Issues**: [Create an issue](https://github.com/hollisho/deepseek-ai-translate-for-translatepress/issues)
- **WordPress Support**: Check the plugin's WordPress.org support forum
- **Documentation**: Refer to TranslatePress documentation for general usage

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This plugin is licensed under the GPLv2 or later license. See the [LICENSE](LICENSE) file for details.

## Credits

- **Author**: Hollis Ho
- **GitHub**: [hollisho](https://github.com/hollisho)
- **Based on**: TranslatePress plugin architecture
- **Translation Service**: DeepSeek AI Platform

---

**Disclaimer**: This plugin is not officially affiliated with DeepSeek or TranslatePress. It's an independent integration created to enhance TranslatePress functionality with DeepSeek's AI translation capabilities.