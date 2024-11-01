=== Simple Slide Show ===
Contributors: alexmansfield
Donate link: http://alexmansfield.com/
Tags: posts, authors
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.1

Displays a slidshow of images using a short code.

== Description ==

This plugin reads images from a directory, resizes them, and dispays them as a slideshow. The default transition effect, slide duration, and slide show dimensions can be set from the Slide Show Options page. These default settings can be overridden from the shortcode if you want to use different values for a certain slide show.

To insert a slide show with a shortcode, use `[simpleslideshow location="/images"]` where "/images" is the location of the folder containing the images. Please note: this path is relative to your WordPress installation.

To call it from within a theme however, you have to wrap it in this PHP function: `<?php echo do_shortcode('[simpleslideshow location="/images"]'); ?>`


== Installation ==

1. Upload the `simple-slide-show` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place `<?php echo do_shortcode('[simpleslideshow location="/images"]'); ?>` in your templates or `[simpleslideshow location="/images"]` in your posts.

== Frequently Asked Questions ==

= No questions yet =

That's right, none so far.


== Changelog ==

= 1.1 =
* Updated timthumb.php script due to security vulnerability.

= 1.0 =
* First version, no changes yet.


== Upgrade Notice ==

= 1.1 =
This version of the Simple Slide Show pluign fixes a security vulnerability that has been discovered in the timthumb.php file included in version 1.0. Please upgrade to version 1.1 immediately to ensure the security of your site.



