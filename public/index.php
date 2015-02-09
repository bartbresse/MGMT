<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

/*FATAL ARROR HANDLING*/

//TODO SHOW A DECENT MESSAGE
/*
register_shutdown_function( "fatal_handler" );
function fatal_handler() {}

/*END FATAL ARROR HANDLING*/

//register url root global
if(!defined('BASEURL')){ define('BASEURL','http://deappdeveloper.nl/MGMT/backend/'); }

try{
	/**
	 * Read the configuration
	 */
	$config = new Phalcon\Config\Adapter\Ini(__DIR__ . '/../app/config/config.ini');
	
	$loader = new \Phalcon\Loader();

	//	$eventsManager = new \Phalcon\Events\Manager();
	/**
	 * We're a registering a set of directories taken from the configuration file
	 */
	$loader->registerDirs(
		array(
			__DIR__ . $config->application->controllersDir,
			__DIR__ . $config->application->pluginsDir,
			__DIR__ . $config->application->libraryDir,
			__DIR__ . $config->application->libraryDir.'/Google/',
			__DIR__ . $config->application->libraryDir.'/MgmtElements/',
			__DIR__ . $config->application->modelsDir,
		)
	);

	$loader->registerNamespaces(
		array( "dbx"    => __DIR__ .  '/../app/library/Dropbox',
			   'Tartan' => __DIR__ . '/../app/library/Tartan',
			   'MGMTForm' => __DIR__ . '/../app/library/MGMTForm',
			   'MgmtEntity' => __DIR__ . '/../app/library/MgmtEntity',
			   'MgmtFile' => __DIR__ . '/../app/library/MgmtFile',
			   'MgmtUtils' => __DIR__ . '/../app/library/MgmtUtils',
			   'Phalcon\Forms' => __DIR__ . '/../app/library/MgmtElements')); 

	// Register some classes
	$loader->registerClasses(
		array(
			"FileUpload" => __DIR__ . $config->application->libraryDir.'/MySQLTableAdapter.php'
		)
	);		   
			   
			   
	/*	
	$eventsManager->attach('loader', function($event, $loader){
		if ($event->getType() == 'beforeCheckPath') 
		{
			echo $loader->getCheckedPath().'<br />';
		}
	});

	$loader->setEventsManager($eventsManager);
	*/
	
	$loader->register();
	
	//$dbxClient = new Dropbox\Client("oJUg9SfNKy0AAAAAAAAAAc-YVEc_2CJSvRmDqJHroAT-JOczZM3tncqJpTzMYDef", "PHP-Example/1.0");

	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new \Phalcon\DI\FactoryDefault();
	$di->set('dbx', function() use ($di){
		$eventsManager = $di->getShared('eventsManager');

		$dispatcher = new Phalcon\Mvc\Dispatcher();
		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	});
	
	/*
		Connect to dropbox
	*/
	$di->set('dbx', function() use ($config){
		return new Dropbox\Client("oJUg9SfNKy0AAAAAAAAAAc-YVEc_2CJSvRmDqJHroAT-JOczZM3tncqJpTzMYDef", "PHP-Example/1.0");
	});
	
	
	/**
	*  Mandrill email client
	*/
	$di->set('mandrill', function() use ($config){
		return new Tartan\Mandrill($config->mail->apikey);
	});
	
	/**
	 * We register the events manager
	 */
	$di->set('dispatcher', function() use ($di) {

		$eventsManager = $di->getShared('eventsManager');
		$dispatcher = new Phalcon\Mvc\Dispatcher();
		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	});

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function() use ($config){
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri(BASEURL);
		return $url;
	});


	$di->set('view', function() use ($config){

		$view = new \Phalcon\Mvc\View();

		$view->setViewsDir(__DIR__ . $config->application->viewsDir);

		$view->registerEngines(array(
			".volt" => 'volt'
		));

		return $view;
	});

	/**
	 * Setting up volt
	 */
	$di->set('volt', function($view, $di) {

		$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

		$volt->setOptions(array(
			"compiledPath" => "../cache/volt/"
		));

		return $volt;
	}, true);

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	 
	if(!defined('DATABASENAME')){ define('DATABASENAME',$config->database->name); }
	 
	$di->set('db', function() use ($config) {
		return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" => $config->database->host,
			"username" => $config->database->username,
			"password" => $config->database->password,
			"dbname" => $config->database->name
		));
	});

	/**
	 * If the configuration specify the use of metadata adapter use it or use memory otherwise
	 */
	$di->set('modelsMetadata', function() use ($config){
		if (isset($config->models->metadata)){
			$metaDataConfig = $config->models->metadata;
			$metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'.$metaDataConfig->adapter;
			return new $metadataAdapter();
		}
		return new Phalcon\Mvc\Model\Metadata\Memory();
	});
	


	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function(){
		$session = new Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});

	/**
	 * Register the flash service with custom CSS classes
	 */
	$di->set('flash', function(){
		return new Phalcon\Flash\Direct(array(
			'error' => 'alert alert-error',
			'success' => 'alert alert-success',
			'notice' => 'alert alert-info',
		));
	});

	/**
	 * Register a user component
	 */
	$di->set('elements', function(){
		return new Elements();
	});

	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
