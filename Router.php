<?php
 /**
 * Ex - A Scalable PHP Framework.
 * @author      Gabriel izebb <gabriel.izebb@gmail.com>
 * @copyright   2014 Gabriel Izebb
 * @link        http://www.gabrielizeb.com/ex
 * @version     1.0
 * @package     Ex
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Ex;

class Router 
{

	private $routes ;

	private $matchedRoute = array();
	
	public function __construct( \Ex\Collection $collection  )
	{
		$this->routes = $collection;
	}	

	public function addRoute( $name, \Ex\Route $route )
	{
		$this->routes->attach($route);
	}

	public function getmatchedRoute(){
		return $this->matchedRoute;
	}
																																																																																																																																																																																																																																																																																																																																																																																									
	public function processRequest()
	{
		foreach($this->routes->all() as  $route) {
			if( $route->matches() && $route->isVaildHttpMethod() ){
				$this->matchedRoute =  $route;
				break;
			}
		}
	}

}

?>
