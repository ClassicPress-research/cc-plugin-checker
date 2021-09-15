=== Classic Commerce Plugin Checker ===
Contributors: Alan Coggins, bedas
Tags: classic commerce, woocommerce, compatibility
Requires at least: 4.9.15
Tested up to: 5.8.1
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A Simple plugin to check WC plugin compatibility with Classic Commerce

== Description ==

Classic Commerce was forked from WooCommerce 3.5.3.
Any plugins with declaring "WC requires at least" above 3.5.3 may not work with Classic Commerce.
This plugin helps you scan the installed Plugins on your website, to spot them easily.

== Changelog ==

= 1.2.0 =
* Add proper uninstall hook
* Make sure only non-existing versions are greyed out

= 1.1.1 =
* Fix issue when disabling/deleting/activating manually renamed plugin

= 1.1.0 =
* Add WC to required plugins

= 1.0.1 =
* Add cap check for plugin links

= 1.0 =
* WPCS compliance
* Security
* Capabilities
* Localization
* OOP

= 0.0.1 =
* Initial Commit
