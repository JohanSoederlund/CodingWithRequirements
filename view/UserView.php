<?php

class UserView
{

	private $session;
	private $user;

	/**
	* Constructor initiates this UserView object
	* @param Session $session model representation of this server session
	*/
	public function __construct(Session $session, User $user)
	{
		$this->session = $session;
		$this->user = $user;
	}

	private function formatLiElements() : string {
		$users = $this->user->getVisibleUsers();
		$li = '<h7 style="color:blue;">ONLINE NOW</h7>';
		//$li = '<h7 style="color:blue;">ONLINE NOW</h7>';
		$i=0;
		foreach	($users as $username) {
			$i++;
			if ($username !== '') {
				$li .= '<li style="color:green;list-style-type:none;">Nick: "'. $username .'"</li>';
			}
		}
		return $li;
	}

	/**
	* List of logged in users
	*/
	public function render() : string
	{
		$output = "";  
		if ($this->session->getLoggedIn()) {
			if ($this->session->getHidden()) {
				$output .= '<h4 style="color:blue;"> - Hidden -   User:'. $this->session->getUserName() .'   - Hidden - </h4>';
			} else {
				$output .= '<h4 style="color:#006400;"> - Visible -   User:'. $this->session->getUserName() .'   - Visible - </h4>';
				$output .= '<ul list-style-type: "none;">'. $this->formatLiElements() .'</ul>';
			}
		}
		return $output;	
	}	

}