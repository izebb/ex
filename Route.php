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

class Route 
{
	/**
	* HTTP Methods 
	* @var array
	*/
	protected $methods = array();

	/**
	* Patten to be matched  
	* @var array
	*/
	protected $pattern;

	/**
	* Paramters of matched params
	* @var array
	*/
	protected $parameters;
	/**
	* Paramters of matched item
	* @var array
	*/
	protected $matcher;

	/**
	* the definition in a route
	* @var array
	*/
	protected $definition;
	/**
	* the server request object handle
	* @var array
	*/
	protected $request;
	
	public function __construct( $definition, \Ex\Matcher $matcher, \Ex\Http\Request $request )
	{
		$this->matcher = $matcher;
		$this->definition = $definition;
		$this->request = $request;
		$this->setHttpMethods($request->getHttpMethods());
	}

	/**
	* Retrieve all the available http methods
	* @return array
	*/
	public function getHttpMethods()
	{
		return  $this->methods;
	}
	/**
	* Retrieve all the available http methods
	* @return array
	*/
	public function getRequestMethod()
	{
		return  $this->definition['method'];
	}

	/**
	* Add Methods to routes
	* @return void
	*/
	public function setHttpMethods( Array $method )
	{
		  $this->methods = array_merge($this->methods, $method);
	}


	/**
	* Validate if a http method exist
	* @var String $method
	* @return boolean
	*/
	public function isVaildHttpMethod()
	{
		return in_array( $this->getRequestMethod(), $this->methods );
	}

	/**
	* Validate if a http method exist
	* @return boolean
	*/
	public function matches()
	{
		return $this->matcher->test();
	}

	/**
	* Retrieve parameters after match
	* @return array
	*/
	public function getParam(){
		if( !$this->matcher )
			return null;
		return $this->matcher->getParam();
	}

	/**
	* Add custom conditions to be used as match
	* @var Array $pattern
	* @return array
	*/
	public function setPattern(Array $pattern)
	{
		$this->matcher->registerPattern( $pattern );
	}

	/**
	* Get all the patterns
	* @var Array $pattern || null
	* @return Array || null
	*/
	public function getPattern( $key = null )
	{
		$pattern = $this->matcher->getPattern($key);
		return $pattern ? $pattern:null;

	}

	/**
	* Dispatch  to repective object for processing 
	* @return Dispatch
	*/
	public function dispatch(\Ex\Dispatcher $dispatcher){
		if(!empty($this->definition))
			return new $dispatcher($this->definition, $this->getParam());
	}
}

?>