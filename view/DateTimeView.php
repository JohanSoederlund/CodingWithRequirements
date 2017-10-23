<?php

class DateTimeView {

	/**
    * Creates a p-tag with a date to show
    * @return string html representation of date obj at this moment,(day, date month Y, Time)
	*/
	public function show() {
		return '<p>' . date('l\, \t\h\e jS \of F Y\, \T\h\e \t\i\m\e \i\s G:i:s') . '</p>';
	}
}
