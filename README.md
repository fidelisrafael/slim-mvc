## Slim MVC

[Slim](https://github.com/codeguy/Slim) is one of the best PHP Frameworks(*IMHO*), but sometimes is annoying for people who like to write webapps in MVC strucuture to adapt to Slim structure, then if you're  bored of using Slim application structure , you should consider give a try to this project.  

The aim of this project is provide a very basic MVC structure to organize your Slim apps.


### Getting started

1 - First of all , clone this repository , now this will be your slim project base.     
2 - Move on clone directy and run `curl -sS https://getcomposer.org/installer | php && php composer.phar install` in the root dir. 	

---
### App configuration flow

So, you've cloned the repository and can wait to start hacking ? Yeah!! 

Let me introduce a few things : most of *MVC* job is done by `routes_helper.php` inside `libs` folder and `base_controller.php` in `app/controllers` folder, if you're curious about how it works, check it out, i'm sure that you'll understand , they're small.

**1 - Adding a new route**

You can simple add a new route in `app/config/routes.php` file by using :

```php
<?php
// The first param is the controller, and seconds is a array of urls
Router\Helper::map("pages", array(
	"(/|/home/?)" 		=> array("get" 	=> "home" , "post" => "home"),	
	"/terms/?"   		=> array("get" 	=> "terms")					 ,
	"/submit/?"			=> array("post" => "submit_form")		 	 ,
));

// News controller (app/controllers/news_controller.php)
Router\Helper::map("news", array(
    "/news/:permalink"  => array("get" => "news_show"),
    "/news/page/:page"  => array("get" => "news_pagination")
));
?>
```

**1.1 - CRUD URLS**     
Above, you see that is very easy to attach urls to controllers, but it's expensive sometimes to write one-by-one url, if you want to do a *CRUD* for a resource, use :  
`app/config/routes.php`     

```php  
// Creating CRUD actions to users model
Router\Helper::mapCRUD("users" , array(
    "confirm_account/:email_token/?" => array("get" => "confirm_account"),
	"set_password/(:token)/?"  => array("get" => "set_password" , "put" => "update_password")
)); 

// This will create the urls and map to `users_controller.php` actions :
// [GET]    /users/         => UsersController#index
// [GET]    /users/:id/     => UsersController#edit
// [GET]    /users/:id/edit => UsersController#show
// [GET]    /users/new/     => UsersController#new
// [POST]   /users/         => UsersController#create
// [PUT]    /users/         => UsersController#update
// [DELETE] /users/:id/     => UsersController#destroy

// The second parameter its called 'extras' routes, and will generate :

// [GET]    /users/set_password/:token => UsersController#set_password
// [PUT]    /users/set_password/:token => UsersController#update_password
```


**2 - Create controller**   
Now, create the file `app/controllers/pages_controller.php` :

```php
<?php
class PagesController extends BaseController {

	protected function _home() {
		$this->page_title = 'Home';
		// simulating a model that brings the latest posts
		$posts  = Posts::recents() ;
		// you can pass multiples params by the way, of course (see _terms action below)
		$this->view_params('posts' , $posts);
		$this->render("pages/index");
	}

	protected function _terms() {
		$this->page_title = 'Our terms';
		// you can pass all kind of value to view
		$this->view_params(array(
			'terms_text' => \SomeAPI::call('get' , 'terms'),
			'terms_date' => \SomeAPI::call('get' , 'terms.date')
		));
		$this->render("pages/terms");
	}

	protected function _submit() {
		// do cool stuff with $_POST, $_FILES :)
	}
}
?>
```

**2.1 - News controller example :** `app/controllers/news_controller.php`	
```php
<?php
class NewsController extends BaseController {

	// this function is EVER called before all others
	public function _init() {
		print_r($this->params);
	}

	protected function _news_show() {
		/* Fetch database and load specific news by permalink */
	}

	protected function _news_pagination() {
		// do news pagination
	}
}

?>
```


**3 - Create view** :   
Until now you have routes and controllers configureds, to finish the flow create a view:    
`app/views/pages/terms.phtml`

```php
<h1>Our terms of services</h1>

<div class='terms-of-service'>
	<?php echo $params['terms_text'] ?> <!-- From controller -->
</div>
```

**4 - Boostraping App** :	
Create `index.php` and make sure you load `app/config/bootstrap.php` file. 	
Default `index.php` : 	

```php
<?php 
class MyApp {
	public static function __init() {
		
		// current dir 
		define("ROOT" , __DIR__); 
 		
 		// Load bootstrap
		require_once ROOT . "/app/config/bootstrap.php";

		AppBootstrap::init(); // init bootstrap
	}
}

MyApp::__init() ; // init app
?>
```

### TODO

- Better documentation
- Tests
- Add Models examples

---
### License
The project is licensed under the MIT license. See LICENSE file for details.

---
### How to Contribute

##### Pull Requests

**1** Fork the `slim-mvc` repository	
**2.** Create a new branch for each feature or improvement	
**3.** Send a pull request from each feature branch to the develop branch	