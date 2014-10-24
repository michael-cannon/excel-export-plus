=== Excel Export Plus ===

Contributors: comprock,ysalame
Donate link: https://aihr.us/about-aihrus/donate/
Tags: excel, export, excel export, csv, csv export, xls, xls export, xlsx, xlsx export
Requires at least: 3.3
Tested up to: 4.1.0
Stable tag: 0.3.0RC1
License: GPLv2 or later

Excel Export Plus easily exports any post type in an Excel-friendly format. Options: Custom Post Types, fields, meta data, filters, and more.


== Description ==

Excel Export Plus allows you to export any type of post data (CPTs included) to excel-friendly formats using an intuitive interface. You have the option to select multiple Post Types, specific fields, meta/custom fields, filter values, and split files by number of records.

Supported export formats are XLS, XLSx, and CSV.

*Warning* File splits of more than 500 records each may not work depending upon your server's PHP memory limit and timeout settings. You can still export 10,000 records. You'll just have 20 files to deal with, than one.

This plugin is platform agnostic by using the open-source PHPExcel library that generates Microsoft-based files without the need to run on a Windows platform.

= FOSS Disclaimer =

Two open source libraries are included with this plugin (without changes).

* [PHPExcel 1.8.0](http://phpexcel.codeplex.com)
* [Bootstrap 2.3.2](https://github.com/twbs/bootstrap)

= Thank You =

Original plugin author is [Yuri Salame](https://profiles.wordpress.org/ysalame/).

Current development by [Michael Cannon](https://profiles.wordpress.org/comprock/) of [Aihrus](http://aihr.us/about-aihrus/).


== Installation ==

1. Upload the contents of the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the Admin section a new menu will be present named "Excel Export +", click there for exporting.


== Frequently Asked Questions ==

= Where do I report an issue? =

In the [support forums](https://wordpress.org/support/plugin/excel-export-plus) or on [GitHub](https://github.com/michael-cannon/excel-export-plus/issues).

= The "Export" is taking too long and generates an error =

This may happen due to memory limitations of your webserver. Unfortunatelly this cannot be bypassed through code and can only be resolved by requesting you webserver to change your memory limits or by turning on the option "Multiple Files" limiting the amount of records within each file. Try to process 500 or less records at a time.

= Why can't I export comments? =

Comments are not yet supported by the plugin. It may be in a future version.

= How do I remove the menu item? =

Use filter `eep_menu_page_capability` to adjust the capability allowed access.


== Screenshots ==

1. First Step : Select Post Types, fields, filters and verify the result
2. Second Step : Select the format and file split
3. Third Setp : Download the files from the list


== Upgrade Notice ==

None


== Changelog ==

= 0.3.0RC1 =
* Change admin menu labeling
* Move screenshots to assets folder
* Removed excess CSS and JavaScript
* Removed support page
* Rename main WordPress file as plugin name
* RESOLVE #1 Post Type media and revisions not being selected for export
* RESOLVE #2 Custom Post Types not selecting for export
* RESOLVE #3 Include taxonomy in export
* RESOLVE #4 xlsx and ods export problems
* RESOLVE #8 Implement configurable capability
* RESOLVE Menu positioning via CSS is off
* RESOLVE parent_id field is unknown
* Revise description
* Update support URL
* Update to PHPExcel 1.8.0

= 0.2 =
* Added Presets capability
* Suppressed php errors
* Corrected a bug in the filter options
* Added an SQL Debug funcionality to view what SQL is being sent to the server to generate the query

= 0.1 =
* First stable release
