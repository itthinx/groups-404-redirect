=== Groups 404 Redirect ===
Contributors: itthinx
Donate link: http://www.itthinx.com/plugins/groups-404-redirect
Tags: groups, access, access control, memberships, members
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 1.6.0
License: GPLv3

Redirect 404's when a visitor tries to access a page protected by Groups.

== Description ==

This plugin redirects 404's caused by hits on pages that are protected by [Groups](http://wordpress.org/plugins/groups/).

The redirection settings can be adjusted in Settings > Groups 404 :

Visits to protected pages can be redirected to:

- a specific post, or
- the WordPress login

The redirection can also be applied for restricted categories, tags and other taxonomy terms if [Groups Restrict Categories](https://www.itthinx.com/shop/groups-restrict-categories/) is used.

The redirect status code can be selected among:

- Moved Permanently (301)
- Found (302)
- See other (303)
- Temporary Redirect (307)

== Installation ==

1. Upload or extract the `groups-404-redirect` folder to your site's `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.  
2. Enable the plugin from the *Plugins* menu in WordPress.
3. Go to Settings > Groups 404 and adjust the redirection settings as desired.

== Frequently Asked Questions ==

= What do the status codes mean? =

Read the section on [Status Code Definitions](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) in [RFC 2616](http://www.w3.org/Protocols/rfc2616/rfc2616.html).

= I have a question, where do I ask? =

You can leave a comment at the [Groups 404 Redirect](http://www.itthinx.com/plugins/groups-404-redirect/) plugin page.

== Screenshots ==

1. Groups 404 Redirect settings.

== Changelog ==

For the full changelog see [changelog.txt](https://github.com/itthinx/groups-404-redirect/blob/master/changelog.txt).

== Upgrade Notice ==

This release has been tested with the latest version of WordPress.
