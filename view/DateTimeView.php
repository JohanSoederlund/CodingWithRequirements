<?php

class DateTimeView {

	//TODO: set timezone, +2 hour
	public function show() {
		return '<p>' . date('l\, jS \of F Y\, \T\h\e \t\i\m\e \i\s G:i:s') . '</p>';
	}
}
