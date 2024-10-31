=== PDW Media File Browser ===
Contributors: canfiax
Donate link: http://constantsolutions.dk
Tags: images, page, post, admin, links
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A very user-friendly & dynamic "Windows 7 theme" media library, with the ability to organize your files in folders. Fast & Simple!

== Description ==

Do you want to simplify your file management? Do you miss the feature to **Create folders**? Tired of the current media library?

Then **PDW Media File Browser** will be something for you! The editor will provide you with a lot of new features!

= Features =
* Create new folders
* Multiple views (large icons, small icons, list, details, etc.)
* Multiple file selection (SHIFT/CTRL click)
* Multiple languages
* Skins (MAC OS X/Windows 7/more)
* 30+ file icons
* Flash file upload (multiply files upload)
* Search
* Filtering (flash/images/media)
* Image caching
* Copy, cut, paste and delete
* File information pane
* Renaming folders and files
* **Works with TinyMCE advanced image feature - see FAQ**
* **Adds 'Select image from file browser' field for Advanced Custom Fields plugin**
* **Standalone support. Integrate the filebrowser with your other plugins. See FAQ**


== Installation ==

1. Upload folder `pdw-file-browser` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to integrate with the TinyMCE editor =

This plugins provides you with the ability to **automaticly integrate** with the 'Advanced Image Button'. All you have to do is get a plugin that add's this feature to your content editor. [Click here for a list of plugins which adds the Advanced Image Button](http://wordpress.org/extend/plugins/pdw-file-browser/other_notes/ "A list of my recommended plugins to add Advanced Image Button")

1. Download, install & activate plugin **Ultimate TinyMCE** or something similar
1. Go to "Settings > Ultimate Tinymce" in the left menu
1. Enable 'Advanced Image Button' at 'Button group 2'
1. Enjoy!

= How does it integrate with 'Advanced Custom Fields' plugin? =

The plugin will do the trick for you! You will find a new field in the dropdown named 'PDW Image select'

== Screenshots ==

1. Select multiply files
1. Change view that suits your standard
1. Example of automatic Tinymce 'Advanced Image button' integration
1. Change skin to feel like a mac browser (skin named Cupertino)
1. The settings page. Change language and skin here

== Changelog ==

= 1.3 =
1. $_GET['wp-root'] problems found and resolved
1. Folder /wp-content/uploads/ will automaticly create itself if it doesn't exist
1. Support for localhost (wamp/xampp) webprogramming (path's fixed)
1. Plugin could not be activated on servers with PHP-version older than 5.3. Problem now resolved and works with older versions of PHP.

= 1.2 =
Security fix to prevent people from accessing files from the evil outside.

= 1.1 =
Adds a new field for ACF (Advanced Custom Fields) named 'PDW Image select'

== Upgrade Notice ==

= 1.0 =
First version realeased.

== Plugins to enable 'Advanced Image Button' ==

Plugins that enable 'Advanced Image Button' in the wordpress editor.

* [TinyMCE Advanced](http://wordpress.org/extend/plugins/tinymce-advanced/ "Enables the advanced features of TinyMCE, the WordPress WYSIWYG editor")
* [Ultimate Tinymce](http://wordpress.org/extend/plugins/ultimate-tinymce/ "Beef up your visual tinymce editor with a plethora of advanced options")

= Example of how to make the file browser show up other text boxes =
When you click the input box below the file archieve will pop up, and you are able to select an image. The image url will be inserted as value in the textbox when selected.
`<input type="text" name="filepath" id="filepath" onclick="openFileBrowser('filepath');" value="Click me!!" />`
