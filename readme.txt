=== Ho DeepSeek For TranslatePress ===
Contributors: hollisho
Donate link: https://hollisho.github.io/
Tags: translatepress, deepseek, translate, translation, multilingual
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

为TranslatePress添加DeepSeek翻译API支持，实现自动化翻译功能。

== Description ==

Ho DeepSeek For TranslatePress 是一个TranslatePress插件的扩展，它为TranslatePress添加了DeepSeek API支持。通过这个插件，您可以使用DeepSeek的API来自动翻译您的WordPress网站内容。

主要特性：

* 支持DeepSeek API
* 简单的配置界面
* 支持多语言翻译
* 与TranslatePress完美集成

= 要求 =

* WordPress 6.0或更高版本
* PHP 7.2或更高版本
* TranslatePress插件

== Installation ==

1. 上传插件到`/wp-content/plugins/`目录，或通过WordPress插件页面直接安装
2. 在WordPress后台启用插件
3. 转到TranslatePress > 设置 > 自动翻译页面
4. 选择"DeepSeek翻译"作为翻译引擎
5. 输入您的DeepSeek翻译API密钥（格式：应用程序ID#应用程序密钥#VOCABID（可选））
6. 保存设置

== Frequently Asked Questions ==

= 如何获取DeepSeek API密钥？ =

1. 访问[DeepSeek开发平台](https://platform.deepseek.com/)
2. 注册/登录账号
3. 创建API keys

= 支持哪些语言？ =

插件支持DeepSeek API支持的所有语言，包括但不限于：
* 中文（简体）
* 中文（繁体）
* 英语
* 日语
* 韩语
* 法语
* 西班牙语
等

= 翻译有字数限制吗？ =

DeepSeek API是按token来计费的，并且有token调用频率。请参考DeepSeek官方文档了解详情。

token 是模型用来表示自然语言文本的基本单位，也是我们的计费单元，可以直观的理解为“字”或“词”；通常 1 个中文词语、1 个英文单词、1 个数字或 1 个符号计为 1 个 token。

一般情况下模型中 token 和字数的换算比例大致如下：

1 个英文字符 ≈ 0.3 个 token。
1 个中文字符 ≈ 0.6 个 token。
但因为不同模型的分词不同，所以换算比例也存在差异，每一次实际处理 token 数量以模型返回为准，您可以从返回结果的 usage 中查看。

== Screenshots ==

1. 设置页面截图

== Changelog ==

= 1.0.0 =
* 初始版本发布

== Additional Info ==

* 插件主页：https://github.com/hollisho/ho-deepseek-for-translatepress
* 开发者主页：https://hollisho.github.io/
* DeepSeek翻译API文档：https://api-docs.deepseek.com/