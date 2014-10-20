=== Plugin Name ===
Contributors: comprock,ysalame
Donate link: http://www.phimind.com/
Tags: excel, export, excel export, csv, csv export, xls, xls export, xlsx, xlsx export, ods, ods export
Requires at least: 3.3
Tested up to: 4.1.0
Stable tag: 0.2

EE+ easily exports any post type in an Excel-friendly format. Options: Custom Post Types, fields, meta data, filters, and more.

== Description ==

EE+ allows you to export any type of post data (CPTs included) to excel-friendly formats using an intuitive interface. You have the option to select multiple Post Types, specific fields, meta/custom fields, filter values and split files by number of records.

Supported formats are XLS, XLSx, CSV and ODS.

This plugin is platform agnostic by using the open-source PHPExcel library that generates Microsoft-based files without the need to run on a Windows platform.

= Development Roadmap (TO-DO List) =

- add the possibility to export comments
- add helper balloons to guide the user

= FOSS Disclaimer =

Two opensource libraries are included with this plugin (without changes)
> PHPExcel version 1.7.9 (2013-06-02) - http://phpexcel.codeplex.com

> Twitter Bootstrap CSS Framework version 2.3.2 (2013-05-17) - http://twitter.github.io/bootstrap

== Installation ==

1. Upload the contents of the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the Admin section a new Menu will be present named PhiMind. On the Sub-Menu "Excel Export +" you will have access to all the plugin functions.

== Frequently Asked Questions ==

= The "Export" is taking too long and generates an error =

This may happen due to memory limitations of your webserver. Unfortunatelly this cannot be bypassed throug code and can only be resolved by requesting you webserver to change your memory limits or by turning on the option "Multiple Files" limiting the amount of records within each file.

= Why can´t I export Comments? =

Comments are not yet supported by the plugin. It will be in the next version.

== Screenshots ==

1. First Step : Select Post Types, fields, filters and verify the result
2. Second Step : Select the format and file split
3. Third Setp : Download the files from the list

== Changelog ==

= 0.2 =
* Revise description

= 0.2 =
* Added Presets capability
* Suppressed php errors
* Corrected a bug in the filter options
* Added an SQL Debug funcionality to view what SQL is being sent to the server to generate the query

= 0.1 =
* First stable release
