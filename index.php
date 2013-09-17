<?php 
/**
* myApp
*
* This class bootstrap your slim MVC project
* 
* @version 0.1
* @author Rafael Fidelis <rafa_fidelis@yahoo.com.br>
*/

class MyApp {
	public static function __init() {
		
		// current dir 
		define("ROOT" , __DIR__); 
 		
 		// Load bootstrap
		require_once ROOT . "/app/config/bootstrap.php";

		AppBootstrap::init();
	}
}

MyApp::__init() ;

?>

