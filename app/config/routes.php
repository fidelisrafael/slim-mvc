<?php 

Router\Helper::map("pages", array(
	"(/|/home)/?"	=> array("get" => "home"),
	"/about_us/?"	=> array("get" => "about_us")
));

// For more(more!!) examples see : //
// https://gist.github.com/fidelisrafael/6592558 //

?>