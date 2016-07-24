<?php

/**
 * @package Band_Statistics
 * @version 1.0
 */
/*
Plugin Name: Band Stats
Plugin URI: 
Description: A plugin that used to manage bands, shows, venues, songs
Author: James Bolongan
Version: 1.0
Author URI: 
*/

//wp_enqueue_style('css', '/wp-content/plugins/bandstatistic/css/bandstats.css');
wp_enqueue_script('bandstatsscript', '/wp-content/plugins/bandstatistic/js/bandstats.js');
wp_enqueue_script('Calendar', '/wp-content/plugins/bandstatistic/js/CalendarPopup.js');

include ('band.php');
include ('song.php');
include ('venue.php');
include ('shows.php');


global $wpdb, $table_prefix,$current_user;

$sql = "SHOW TABLES FROM ".DB_NAME."";
$result = mysql_query($sql);

if (!$result) {
	echo "DB Error, could not list tables\n";
	echo 'MySQL Error: ' . mysql_error();
	exit;
}

while ($row = mysql_fetch_row($result)) {
	$tables[] = $row[0];
	$parsetable = explode('_',$row[0]);
	$tablenames[] = $parsetable[1];
}

$table_bands = $table_prefix.'bs_bands';
if (!in_array($table_bands, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_bands` (
					  `id` int(20) NOT NULL auto_increment,
					  `usercreatorid` int(20) default NULL,
					  `imagefilename` varchar(100) default NULL,
					  `bandname` varchar(50) default NULL,
					  `bio` varchar(100) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `usercreatorid` (`usercreatorid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

$table_bandlinks = $table_prefix.'bs_bandlinks';
if (!in_array($table_bandlinks, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_bandlinks` (
					  `id` int(20) NOT NULL auto_increment,
					  `usercreatorid` int(20) default NULL,
					  `bandid` int(20) default NULL,
					  `links` varchar(100) default NULL,
					  `linktitle` varchar(100) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `usercreatorid` (`usercreatorid`),
					  KEY `bandid` (`bandid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

$table_songs = $table_prefix.'bs_songs';
if (!in_array($table_songs, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_songs` (
					  `id` int(20) NOT NULL auto_increment,
					  `usercreatorid` int(20) default NULL,
					  `bandid` int(20) default NULL,
					  `songtitle` varchar(100) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `usercreatorid` (`usercreatorid`),
					  KEY `bandid` (`bandid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

$table_venues = $table_prefix.'bs_venues';
if (!in_array($table_venues, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_venues` (
					  `id` int(20) NOT NULL auto_increment,
					  `usercreatorid` int(20) default NULL,
					  `venuename` varchar(100) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `usercreatorid` (`usercreatorid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

$table_shows = $table_prefix.'bs_shows';
if (!in_array($table_shows, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_shows` (
					  `id` int(20) NOT NULL auto_increment,
					  `usercreatorid` int(20) default NULL,
					  `showname` varchar(100) default NULL,
					  `date` date default NULL,
					  `venueid` int(20) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `usercreatorid` (`usercreatorid`),
					  KEY `venueid` (`venueid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

$table_showsongs = $table_prefix.'bs_showsongs';
if (!in_array($table_showsongs, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_showsongs` (
					  `id` int(20) NOT NULL auto_increment,
					  `usercreatorid` int(20) default NULL,
					  `showid` int(20) default NULL,
					  `songid` int(20) default NULL,
					  `songorder` int(20) default NULL,
					  `set` varchar(30) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `usercreatorid` (`usercreatorid`),
					  KEY `showid` (`showid`),
					  KEY `songid` (`songid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

$table_show_userattended = $table_prefix.'bs_show_userattended';
if (!in_array($table_show_userattended, $tables)) {		
	$result = mysql_query("
					CREATE TABLE IF NOT EXISTS `$table_show_userattended` (
					  `id` int(20) NOT NULL auto_increment,
					  `showid` int(20) default NULL,
					  `vbulletin_userid` int(20) default NULL,
					  PRIMARY KEY  (`id`),
					  KEY `showid` (`showid`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8;"
				);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
}

add_action('admin_menu', 'band_menu');
function band_menu() {
	add_menu_page('Band', 'Band Stats', 'administrator', 'band', 'band_function');
	add_submenu_page( 'band', 'Shows', 'Shows', 'administrator', 'shows', 'shows_function');
	add_submenu_page( 'band', 'Venues', 'Venues', 'administrator', 'venues', 'venues_function');
	add_submenu_page( 'band', 'Songs', 'Songs', 'administrator', 'songs', 'songs_function');
}