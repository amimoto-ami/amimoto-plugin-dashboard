=== AMIMOTO Plugin Dashboard ===
Contributors: hideokamoto,amimotoami
Donate link: https://amimoto-ami.com
Tags: admin,amimoto
Requires at least: 4.4.0
Tested up to: 4.6.1
Stable tag: 0.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Controle AMIMOTO helper plugins

== Description ==

This plugin controlling AMIMOTO helper plugins. You can easy use AMIMOTO helper plguin in this plugin admin panel.
Now supporded following plugins.

- Nginx Cache Controller: Control Nginx reverse proxy cache.
- C3 CloudFront Cache Controller: Control Amazon CloudFront.
- Nephila Clavata: Upload media file to Amazon S3.

== Installation ==

You can two ways to install this plugin.

a. Upload this directory to `/wp-content/plugins/` directory.
b. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 0.4.0 =
* Add  filter to add CloudFront content

= 0.3.0 =
* Add Email address patch (for using default.conf website)

= 0.2.0 =
* Add AMIMOTO FAQ Search Widget

= 0.1.0 =
* Add AMIMOTO News Widget

= 0.0.1 =
* Initial Release

== Upgrade Notice ==

= 0.4.1 =
* Re-set c3_settings filter for old jinkei stack
