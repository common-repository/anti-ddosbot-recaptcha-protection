=== Anti DDOS/BOT reCAPTCHA Protection ===
Plugin URI: http://wordpress.org/plugins/anti-ddosbot-recaptcha-protection
Version: 1.0.0
Author: https://www.webtouch.gr
Author URI: https://www.webtouch.gr
License: LGPL v2.1
Text Domain: anti-ddosbot-recaptcha-protection
Tags: protection, ddos, bot, attack, anti ddos, anti bot, reCAPTCHA, lock page, protect
Requires at least: 4.7.
Tested up to: 6.1.1
Stable tag: 1.0.0
Contributors: webtouchgr
Frequently Asked Questions (FAQ):
Changelog: 2023-02-13: Announce Version 1.0

== Screenshots ==
1. Main Configuration Page
2. Main Configuration Page
3. Protection message in Website

== Description ==
Protect Your Website with Our Anti DDOS / BOT Protection

Details :
Enable this Plugin Only if you are under DDOS/BOT Attacks
We not resolve the DDOS Problem, but we try to limit bad traffic
With our plugin, you'll be able to secure your website against potential DDOS and BOT attacks.
Visitors will be required to complete a one-time reCAPTCHA verification
That helps to minimize the amount of data transferred between your website and the attacker.
Once the reCAPTCHA check has been passed, visitors will be granted full access to your website content.

Advance :
If you can't access on wordpress control panel but you still have access with SSH or FTP
You can enable / disable this protection adding in wp-config.php this option
define( 'ANTI_DDOS_ENABLED', true );
Above of the line "require_once ABSPATH . 'wp-settings.php';"
Protection ignore the checkbox inside the plugin config page

Protect only specified URLs :
If attackers concentrate their efforts on specified URLs, you can secure only those URLs
You can use either the entire URL or a portion of it,
Using the word "product" then protection enabled for all URLs containing this word.
To protect the entire website, leave the URLs field empty.

== Installation ==

= Easy Way: =
1. Go to the WordPress > Plugins > Add New
2. Search For "Anti DDOS/BOT reCAPTCHA Protection".
3. Install with one click

= Manual Way: =
1. Upload "anti-ddosbot-recaptcha-protection.zip" to the `/wp-content/plugins/` directory
2. Extract files in the folder "anti-ddosbot-recaptcha-protection"
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.0.0 - 16/02/2023 =
* Announce: First Release of this plugin
