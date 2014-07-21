<?php
/**
* 
*/
namespace Ex;
class Ex 
{

	public static function route( $pattern,  Array $definition ){
		
		$server = new \Ex\ServerEnv();
		$collection =  new \Ex\Collection();
		$matcher = new \Ex\Matcher( $server['pretty_uri'], $pattern);
		$request = new \Ex\Http\Request($server , array());
		$route = new \Ex\Route($definition, $matcher, $request);

		$router = new \Ex\Router($collection);
		$router->addRoute( $pattern, $route );
		$router->processRequest();
		$matched  = $router->getmatchedRoute();


	}


	
}


?>