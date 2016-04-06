=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: http://www.laurentschaffner.com
Tags: auto, select, first, result
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin simply redirect to the matching article when someone search it using its exact title name.

== Description ==

As said within the short description this plugin only redirect users when they tape the exact title words. It is useful when your WordPress site/blog got an autocomplete.

== Installation ==

Just unzip the plugin within WordPress

1. Upload `auto-select-result.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy !

== Frequently Asked Questions ==

= When I search something, there's a blank page =

Just deactivate the ob_start functionality within the plugin code (the 'init'  handler)

= Everything blow up when I activated the plugin =

Deactivate the plugin and remove it ; I think it won't work anyway.

== Screenshots ==

== Changelog ==

= 0.0.1 =
* What a great day ; the plugin was created.

== Arbitrary section ==
