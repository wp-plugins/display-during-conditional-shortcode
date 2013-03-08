<?php
/*
Plugin Name: Display During Conditional Shortcode
Plugin URI: 
Description: Display content conditionally using shortcodes.  To hide content after a certain date and time, in this example June 27, 2015 at 10:00 am use the following format: [display_during end_day_time="June 27, 2015 10:00 am" message="Sorry, this content no longer available."]CONTENT_TO_DISPLAY[/display_during].  To show content during an interval, for example from Sunday at 8:00 am to Monday at 8:00 pm, use the following format: [display_during start_day_time="Sun 8:00 am" end_day_time="Mon 8:00 pm" timezone_location="America/Denver" message="Sorry, this content is not currently available."]CONTENT_TO_DISPLAY[/display_during].  This example also demonstrates the ability to specify a timezone to use if the one set by the blog isn't correct.  If you don't want to display any message if the content is not to be shown, omit the "message" attribute and nothing will be shown at all.
Donate URI: 
Author: Gabriel Serafini (ShareThePractice.org)
Author URI: http://sharethepractice.org/
Version: 1.1

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*/ 


/**
 * Shortcode functionality
 *
 * @param mixed $atts Optional. Attributes to use in shortcode
 * @return $content if not expired or valid interval
 */
function stp_display_during_shortcode($atts = array(), $content = '') {

	// Try to get built-in default timezone for site
	$timezone_string = get_option('timezone_string');
	if ($timezone_string == "") {
		// We <3 CA
		$timezone_string = 'America/Los_Angeles';
	}

	extract(shortcode_atts(
		array(
			'start_day_time' => 'now',
			'end_day_time' => '',
			'timezone_location' => $timezone_string,
			'message' => '',
		),
		$atts
	));


	if ($end_day_time == "") return;

	date_default_timezone_set($timezone_location);

	if ($start_day_time != 'now' && strtotime($start_day_time) > strtotime($end_day_time)) {
		$start_day_time = "last $start_day_time";
	}

	$now_timestamp = strtotime("now");
	$start_timestamp = strtotime($start_day_time);
	$end_timestamp =  strtotime($end_day_time);

	if ($now_timestamp >= $start_timestamp && $now_timestamp < $end_timestamp) {
		return do_shortcode($content);
	}
	else {
		return do_shortcode($message);
	}

}

// Register shortcode
add_shortcode('display_during', 'stp_display_during_shortcode');

?>