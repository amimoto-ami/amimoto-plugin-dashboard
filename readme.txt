=== AMIMOTO Plugin Dashboard ===
Contributors: hideokamoto,amimotoami
Donate link: https://amimoto-ami.com
Tags: admin,amimoto
Requires at least: 4.4.0
Tested up to: 5.7.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Control AMIMOTO helper plugins

== Description ==

This plugin handles AMIMOTO helper plugins.
You can easy use AMIMOTO helper plugins in the plugin admin panel.
Now supports the following plugins.

- Nginx Cache Controller: Control Nginx reverse proxy cache.
- C3 CloudFront Cache Controller: Control Amazon CloudFront.

== Installation ==

Two ways to install this plugin
You can two ways to install this plugin.

A: Install through WordPress dashboard
1. Go to Plugin page on your WordPress dashboard
2. Click [Add New]
3. Input amimoto dashboard into search form
4. Click [Install Now]
5. Click [Activate]

B: Install through FTP or SFTP
1. Upload the plugin folder to /wp-content/plugins/ directory on your server.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 1.0.1 =
* Fixed PHP 8 compatibility issues while maintaining PHP 7 compatibility
* Updated GitHub Actions workflow to use wp-env for testing


= 1.0.0 =
* Rewrite entire code

= 0.6.1 =
* Fix  bug

= 0.6.0 =
* Nephila Clavata has been retired

= 0.5.3 =
* Fix small bug

= 0.5.2 =
* Fix small bug

= 0.5.1 =
* Fix distirbution id bug

= 0.5.0 =
* Fix small bug
* Add some feature for AMIMOTO Managed hosting user

= 0.4.1 =
* Re-set c3_settings filter for old jinkei stack

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

= 0.6.1 =
* Fix  bug
