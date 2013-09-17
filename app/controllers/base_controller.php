<?php 

/**
* Base Controller
*
* This class is responsible to handle templates renders and get data from view and pass it to 
* another controllers.
*
* @version 1.0
*/
class BaseController {

	public $app 						;	/* Framework App(Router) */
	public $request 					;	/* App Request Object */
	public $params 						;	/* App request Params */
	public $request_params 				; 	/* Alias for $request->params */
	public $page_title					; 	/* Page title */
	
	/**
	 * Single instance of Controller
	 *
	 * @var BaseController
	 **/
	public static $instance = NULL ;
	
	/**
	 * Params passed to view
	 *
	 * @var array
	 **/
	protected $view_params  = array()	;  


	/**
	* Class constructor
	* 
	* Set up params(from URL and request) from router app, set the action to be called when instantiate the class
	* @param array  $params Params(from url and HTTP requests) from view
	* @param string $action Action to be called in controller
	* @param mixed  $app The object responsible to handle routes and render views(Actually Slim Framework app)
	* @return BaseController instance
	*/
	public function __construct($params , $action , $app) {

		$this->app            = $app;
		
		$this->request        = $this->app->request();
		$this->request_params = $this->request->params();
		$this->params         = $params;

		/* Callback to be called in all others classes to setup things :D */
		$this->_init();	

		/* Current action anme */
		$this->action_name = $action ; 

		/* Insert dash to prevent erros with functions names (eg : new - reserved word) */
		$action   = "_" . $action;

		if(method_exists($this,$action)) {
			// call the action , process the data and render the view :)
		 	$this->$action();
		} else {
		  	$this->__notFound();
		}

	}

	// Function called after controller creation (such as before_filter)
	public function _init() { }


	/**
	* Static Get Instance
	* 
	* Get unique instance of Controller
	* @return BaseController instance
	*/
	public static function getInstance($params,$action,$app) {
		$class_name     = get_called_class();
		$instance_class_name = get_class($class_name::$instance);
		if($class_name != $instance_class_name || !isset($class_name::$instance) || is_null($class_name::$instance))
			$class_name::$instance = new $class_name($params,$action,$app);
		
		return $class_name::$instance;
	}


	/**
	* Assign a value to be send to view template (via $params array)
	* @access protected
	* @param $key Key name
	* @param $value value to be assigned
	*/
	protected function view_assign($key,$value=null) {
		if(is_array($key)) return $this->view_params = array_merge_recursive($this->view_params,$key);
		return $this->view_params[$key] = $value;
	}

	/**
	* Render a view template with controller params and data
	* @param $controller Controller name (used to find the folder view folder)
	* @param $action Controller action name (used to find the file basename)
	* @param $params Params that will sended to view in $params array
	* @param $layout Layout filename
	*/
	public function render($path, $params=array(), $layout = true) {

		$data = explode("/" , $path) ; $controller = array_shift($data) ; $action = array_shift($data);

		$view_filename = ($this->app->config("templates.path") . "{$controller}/{$action}.phtml");

		if(isset($controller) && !empty($controller)) {
			// Merge params from view ($this->view_assign)
			$params = array_merge_recursive($params,$this->view_params, $_REQUEST);

			// render the view template using Slim template engine
			$this->app->render($this->app->config("layout.file") ,  
					array( 
						"view_filename" =>	$view_filename , 
						'page_title'	=>  $this->page_title,
						'page_body'     => 	"{$controller} {$controller}-{$action}" , 
						'params'        => 	$params , 
						'controller'    => 	$controller , 
						'action'        => 	$action 
					)
			);
		}
	}

	// render partial
    public function renderPartial($partial, $data) {
            ob_start();
            extract($data);
            require sprintf("%s/%s/_%s.phtml" , VIEWS_FOLDER , "_partials" , $partial);
            echo ob_get_clean();
    }
	
	// redirect the user with a flash message
	protected function redirect($url) {
		$this->app->redirect($url);
	}

	// serve a $response encoded as JSON value
	protected function JSONResponse($response) {
		return print_r(json_encode($response));
	}

	protected function __notFound() {
	    header('HTTP/1.0 404 Not Found');
	    die("<pre>Method {$this->action_name} not found in " . get_called_class() . "</pre>");
  	}

}
 ?>