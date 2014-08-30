<?php 
/**
* Routher Helper
*
* This class is a utility to deal with routes in Slim Framework
* 
* @version 0.1
* @author Rafael Fidelis <rafa_fidelis@yahoo.com.br>
*/

namespace Router;
class Helper {
	
	/**
	 * Router Cache
	 */	
	public static $cache = array();

	/**
	 * This function map all CRUD urls to specific controllers actions. (automatically renders views)
	 *	
	 * @return void
	 **/
	public static function mapCrud($resource, $extra=array()) {

		$app = self::getAppInstance();
		
		$paths = array(); // URL paths

		foreach($extra as $path => $action) {
			if(is_array($action)) {
				foreach ($action as $method => $_action)
					$paths[$_action] = array("method" => $method , "url" =>  "/%s/{$path}");
			} else {
				$paths[$action] = array("method" => "get" , "url" =>  "/%s/{$path}");	
			}
		}

		/* Basic CRUD paths */
		$paths = array_merge($paths ,array(
			"create"   	=> array("method" => "post"   , "url" => "/%s/?")		, 
			"update"   	=> array("method" => "put"	  ,	"url" => "/%s/?")		, 
			"destroy" 	=> array("method" => "delete" ,	"url" => "/%s/:id/?")	, 
			"new"    	=> array("method" => "get"	  ,	"url" => "/%s/new/?")	, 
			"show"		=> array("method" => "get"	  , "url" => "/%s/:id/?")	,
			"edit"		=> array("method" => "get"	  ,	"url" => "/%s/:id/edit"),
			"index"		=> array("method" => "get"	  , "url" => "/%s/?")
		));

		foreach ($paths as $action => $data) {
			list($method,$url) = (array_values($data));
			$app->$method(sprintf($url,$resource) , function() use($app, $action, $resource,$url) {
				// Parse the URL and return a array of params .
				// Eg. /books/:id/? . Route : /books/os-miseraveis/ will return Array(":id" => os-miseravei)
				$params = Helper::getRequestParams(func_get_args(), $url);

				$class     = Helper::buildClassName($resource);
				$_resource = $class::getInstance($params, $action, $app); 
				// render the view automagically :)
				return $_resource->render($resource,$action,$params);
			});
			
		};
	}

	/**
	* This functions map urls to controller actions
	*
	* @return mixed Controller
	**/
	public static function map($controller, $paths) {
		$app = self::getAppInstance();

		foreach($paths as $url => $path) {
			foreach($path as $method => $action)
				$app->$method($url, function() use($controller,$action,$url) {
					$params = Helper::getRequestParams(func_get_args(), $url);
					return Helper::dispatchRequest($controller, $action, $params);
				});
			}
	}

	/**
	 * Dispatch a request to controller
	 *
	 * @return mixed Controller
	 **/
	public static function dispatchRequest($controller, $action , $params=array()) {
		$class            = Helper::buildClassName($controller);
		return $_resource = $class::getInstance($params, $action, self::getAppInstance());
	}

	/**
	 * Parse URL key params
	 *
	 * @return array all params extracted from URL
	 * @author 
	 **/
	public static function getRequestParams($args,$url) {
		$params = array();
		/* Get all placeholders for variables in URL */
		preg_match_all("/:[\w]+/", $url, $matches);
		/* set values */
		foreach ($args as $key => $value) { $params[str_replace(":", '' , $matches[0][$key])] =  $value ; }
		return $params ;
	}

	/**
	 * Build a class name based in filename (summary: capitalize first word and add Controller)
	 *
	 * @return mixed Controller
	 **/
	public static function buildClassName($controller) {
		require_once CONTROLLERS_FOLDER . "{$controller}_controller.php";
		return $class = ucfirst($controller) . "Controller";
	}

	/**
	 * Return the current Slim framework isntance
	 *
	 * @return Slim\Slim
	 **/
	public static function getAppInstance() {
		return \Slim\Slim::getInstance();
	}
}
?>
