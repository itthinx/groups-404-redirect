=== Groups 404 Redirect ===
Contributors: itthinx
Donate link: http://www.itthinx.com/plugins/groups-404-redirect
Tags: 301, 302, 303, 307, 404, access, access control, capability, capabilities, content, download, downloads, file, file access, files, group, groups, member, members, membership, memberships, permission, permissions, redirect, redirection, subscription, subscriptions
Requires at least: 3.3
Tested up to: 3.9.2
Stable tag: 1.2.3
License: GPLv3

Redirect 404's when a visitor tries to access a page protected by Groups.

== Description ==

This plugin redirects 404's caused by hits on pages that are protected by [Groups](http://wordpress.org/extend/plugins/groups/).

The redirection settings can be adjusted in Settings > Groups 404 :

Visits to protected pages can be redirected to:

- a specific post, or
- the WordPress login

The redirection can also be applied for restricted categories, tags and other taxonomy terms if [Groups Restrict Categories](http://www.itthinx.com/plugins/groups-restrict-categories/) is used.

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

= 1.2.3 =
* Added a fix to circumvent a bug in some PHP versions related to the usage of method_exists() with an inexistent class.

= 1.2.2 =
* Added check for post restricted by term.

= 1.2.1 =
* WordPress 3.9.1 compatibility checked.

= 1.2.0 =
* Added support for taxonomy term redirection with Groups Restrict Categories.

= 1.1.3 =
* Fixed a redirect loop when redirecting visitors to the WordPress login.
* WordPress 3.8 compatibility checked.

= 1.1.2 =
* Added the `groups_404_redirect_post_id` filter to allow fine-tuning redirection by post ID.

= 1.1.1 =
* Improved the wording on the settings page.
* Added a link to RFC 2616's [Status Code Definitions](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) in the settings.
* WordPress 3.6 compatibility tested

= 1.1.0 =
* Improvement : Added support for Custom Post Types.
* Improvement : Avoiding circular redirection (for example when the target post is also protected).

= 1.0.1 =
* Added link to Settings on Plugins screen.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.2.3 =
* Added a fix to circumvent a bug in some PHP versions.

= 1.2.2 =
* Added support for redirection on posts restricted by terms.

= 1.2.1 =
* WordPress 3.9.1 compatibility checked.

= 1.2.0 =
* This version supports redirection for restricted categories, tags and taxonomy terms when [Groups Restrict Categories](http://www.itthinx.com/plugins/groups-restrict-categories/) is used.

= 1.1.3 =
* Fixed a redirect loop when redirecting visitors to the WordPress login. WordPress 3.8 compatibility checked.

= 1.1.1 =
* WordPress 3.6 compatibility checked and better info on the settings page.

= 1.1.0 =
* Now supports redirection for Custom Post Types.

= 1.0.1 =
* Added Settings link.

= 1.0.0 =
* Initial release.
