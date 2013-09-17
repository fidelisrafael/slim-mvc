<?php 

class AppBootstrap {

	/**
	* Init system
	* 
	* Load all required files and setup configs.
	* @param boolean $runApp If false dont load app framework - false during tests
	* @return boolean true
	*/
	public static function init($runApp=true) {

		// Base config file
		require_once ROOT . "/app/config/config.php" ;

		// Returns a array with app configuration ( and run anothers configs)
		$config = AppConfig::get();

		if($runApp) {

			require VENDOR_FOLDER . '/autoload.php'; 

			$app = new \Slim\Slim();
			$app->config($config);
			
			require_once LIB_FOLDER . "/routes_helper.php";
			
			/* Routes File */
			require_once ROOT . "/app/config/routes.php" ;

			/* Run App */
			$app->run();
		}

		return TRUE ; 
	}
}

?>