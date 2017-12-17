=== Admin Code Editor ===
Contributors: stevepuddick
Tags: code, editor, html, css, javascript, admin, post, code, sass, scss, less
Requires at least: 4.5
Tested up to: 4.9
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add additional HTML, CSS, and JS to a post. Features colour highlighted text, line numbers, tab indents, and more. SCSS, LESS support too.

== Description ==

Easily add additional HTML, CSS, and JavaScript to post, page, or other custom post type. 

The built in WordPress content editor provides a ‘text’ tab that can be used to enter HTML and even CSS and JavaScript when wrapped in a &lt;style&gt; or &lt;script&gt; tag. This approach has many limitations and drawbacks. Admin Code Editor addresses these issues and adds additional features. 

[youtube https://www.youtube.com/watch?v=2ezFdHuEBD8]

Here are the key highlights:

* Code is highlighted with various colours according to the code type specified. This looks similar to popular desktop code editors such as Sublime Text, PhpStorm, or Coda. It makes reading code much easier
* Line numbers
* Search and replace strings in your code (CMD/CTRL + F)
* Single or multiline tabbed indenting
* wpautop disabled for code output
* shortcodes can be placed within the HTML code
* Ability to disable code output on templates (home, front-page, search, etc)
* Ability to place code output above or below normal post content on the front end
* Resizable code windows (click and drag bottom of code window)

**New in 1.3.0**

* update preprocessors to latest versions
* allow HTML code to be queried in WordPress website searches
* creation of CSS 'isolation modes' to apply CSS to the HTML code, post content, or full webpage

Admin Code Editor also supports server side compilation of various popular preprocessors including: SCSS, LESS, HAML, MarkDown, and CoffeeScript. Here are some highlights related to the server side compilation of preprocessor code:

* displays compilation errors in editor interface
* view compiled code
* code editor highlighting adjusts based on the preprocessor selected

Thanks to the various open source tools and technologies that has made the creation of Admin Code Editor possible:

* [Ace](https://ace.c9.io/)
* [Bootstrap](http://getbootstrap.com/)
* [Backbone.js](http://backbonejs.org/)
* [jQuery](https://jquery.com/)
* [CoffeeScript PHP](https://github.com/alxlit/coffeescript-php)
* [lessphp](https://github.com/leafo/lessphp)
* [PHP Markdown](https://github.com/michelf/php-markdown)
* [scssphp](https://github.com/leafo/scssphp)
* [HamlPHP](https://github.com/hamlphp/HamlPHP)
* [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)


== Installation ==

1. From the plugin management page in your WordPress admin add a new plugin. Search for "Admin Code Editor" (The plugin can also be downloaded directly at https://wordpress.org/plugins/wp-ace-editor)
2. Activate the plugin
3. Go to "Settings" > "Admin Code Editor" to modify the default settings. In particular, you want to make sure your desired post types are enabled.
4. Edit or create a new post, of a 'post type' that has been enabled. 
5. Edit the Admin Code Editor settings for that post and override any default settings, if desired.
6. Enter the HTML, CSS, and/or JavaScript in the Admin Code Editor for that post. Be sure to write your code in accordance with the preprocessor you have selected.
7. Update/Publish the post as usual to save your Admin Code Editor code and settings
8. View the front end view of your post to see your code rendered 


== Frequently Asked Questions ==

= The options for “Only display when” are “Inside the Loop” and “In Main Query”. What does this mean? =

See the [WordPress Conditional Tags](https://codex.wordpress.org/Conditional_Tags#Is_Main_Query) resource for more information.

= There is the option to not display Admin Code Editor code on the “Front Page”, “Home”, “Archives”, and “Search Results” templates. What does this mean? =

See the [WordPress Conditional Tags](https://codex.wordpress.org/Conditional_Tags#The_Main_Page) resource for more information.

= How did you get Bootstrap to work in the WordPress admin without conflicting with other components? =

Running the compiled Bootstrap CSS through another round of preprocessing. On this additional round, the CSS is simply wrapped in an additional tag to isolate all the styles to the WP ACE components. A few adjustments were still needed.

= I am using jQuery in my JavaScript code and I am getting an "Uncaught TypeError: $ is not a function" error. How do I fix this?  =

Admin Code Editor includes the jQuery file that comes with your current version of WordPress. This ensures that your website is always using an up to date version of jQuery when you update. But, this file also sets jQuery to "No Conflict" mode to prevent jQuery from conflicting with other JavaScript libraries. The use of the '$' is what is causing the problem. You can solve this by replacing '$' with 'jQuery' in your code. Anothe approach is to put the line `$ = jQuery.noConflict(true);` at the top of your JavaScript code. This will allow you to continue to use '$' in your code. 

= I can see errors in my code but I do not see any error notification in the Admin Code Editor interface. What is going on? =

Admin Code Editor may not highlight all errors in your code or state all preprocessor compilation errors. It is best practice to inspect your code on the front end of your website with a tool such as Chrome Inspector to verify there are no errors.

= I have entered some CSS but it is not being applied to elements outside of my HTML code. What is going on? =

Admin Code Editor has been designed to isolate all CSS styling to only elements within the Admin Code Editor HTML editor. 

= When I preview my post I don't see the recent updates to my code. What is going on? =

At the current moment, previewing a post will not show updates to your Admin Code Editor code. In order to see updates, you will need to update or publish the post.  

= How can I include external scripts and styles in WP ACE Editor? =

At the current moment there is not an elegant way of doing this. However, you can include scripts using the `<script>` tag in the HTML code area.  

= How can I make my HTML code searchable? =

First, ensure the "Allow Searchable HTML" has been set to "Yes". The "HTML tag stripped" version of the HTML code will now be present in the `_wp_ace_html_php_filtered_html` meta data for that post. Many popular entended search plugins (or a custom implementation) can now utilize this meta data for search results.  


== Screenshots ==

1. The Admin Code Editor HTML, CSS, and JavaScript is output below the regular post content
2. Specify the default settings for the Admin Code Editor here
3. The Admin Code Editor HTML Editor. 
4. The Admin Code Editor CSS Editor. In this screenshot SCSS is being used as the preprocessor and an error is currently being displayed.
5. The Admin Code Editor JavaScript Editor.
6. Settings modal window with the General tab active.
7. Settings modal window with the HTML tab active.
8. Settings modal window with the CSS tab active.
9. Settings modal window with the JavaScript tab active.


== Changelog ==

= 1.3.0 =
* Add: update preprocessors to latest versions
* Add: allow HTML code to be queried in WordPress website searches
* Add: creation of CSS 'isolation modes' to apply CSS to the HTML code, post content, or full webpage
* Fix: code properly saves empty value after completely removing all previous code
* Change: improve usability of tab switching in editor interface

= 1.1.0 =
* Fix: jQuery toggle display
* Fix: Update ACE Editor (https://ace.c9.io/) to latest version.  Fixes 'jumping' to top of page when editor is right clicked
* Change: Remove wp_kses_post filtering on HTML content to allow for more flexible/versitile HTML code

= 1.0.2 =
* Fix: Stricter regex to filter out attempted closing of &lt;style&gt; and &lt;script&gt; tags in code
* Fix: Some important special characters encoded in CSS filtered output

= 1.0.1 =
* Fix: HTML special characters encoding in JavaScript and CSS code, producing errors in compiled code on front end
* Fix: Proper risizing of code editor windows
* Fix: Proper CSS z-index for WordPress sidebar/top menu when Admin Code Editor modal is active  

= 1.0.0 =
* Initial Release



