<?php
header('Content-type:text/json');

require_once "ServerEnv.php";
require_once "Request.php";
require_once "Matcher.php";
require_once "Collection.php";
require_once "Dispatcher.php";
require_once "Route.php";
require_once "Router.php";
require_once "Ex.php";

$pattern = array(
        'any'=>'.',
        'num'=>'[0-9]+',
        'alpha'=>'[A-z]+',
        'alnum'=>'[a-zA-Z0-9]*',
);

\Ex\Ex::route('aa/<alnum>/<num>', array(
    'method'=>'GET',
))
/*$match = 'aa/<alnum>/<num>';
$matcher = new \Ex\Matcher($scriptName, $match);
$built = $matcher->build();
$param = $matcher->getParam();
var_dump($built, $param);
*/
/*$match = new Ex\Matcher();
Ex\Ex::Call('Matcher')->addPattern(

)
Ex\Route::routeCollection(
    ':p<num>/',
    array(

    ),
    array(
    ),
    array(
    ),
);

$route1 = Ex\Route::add('', array(
    )
);

$router = Ex::route(
    array(
        'match'=>':p<num>/',
        'controller'=>array(
            'class'=>'',
            'action'=>'',
            'param'=>array(':p','gabriel');
        ),
        'content'=>'json'
    ),
    array(
    )

);

$router->run()->onNotFound(function(){

});*/

?>