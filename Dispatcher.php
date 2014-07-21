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

class Dispatcher
{
	

	protected $def;
	protected $param;
	protected $callable;
	protected $controller;
	protected $actions;
	
	public function __construct( $def, $param )
	{
		$this->def = $def;
		$this->param = $param;
	}
	
	public function getParam(){
		return isset($this->def['param']) ? $this->def['param'] : null;
	}

	public function filterParam(){
		if( is_null($this->getParam() ) ){
			return $this->$param;
		}
		$param = array();
		foreach( $this->getParam() as $key ) {
			$param[] = $this->param[$key];
		}
		$this->param = $param;
	}

	public function setController( $controller ){
		$this->def['controller'] = $controller;
		return $this;
	}

	public function setAction( $action ){
		$this->def['action'] = $action;
		return $this;

	}
	
	public function getCallable(){
		return isset($this->def['call']) ? $this->def['call'] : null;
	}

	public function getController(){
		return isset($this->def['controller']) ? $this->def['controller'] : null;
	}
	
	public function getAction(){
		return isset($this->def['action']) ? $this->def['controller'] : null;
	}

	public function runController(){
		$controller = $this->getController();
		if( $controller ){
			if( class_exists( $controller ) ){
				$action = $this->getAction();
				$action = $action ? $action : 'index';
				$controllerObj  = new \ReflectionClass( $controller );
				if( $controllerObj->hasMethod( $action ) ){
						$method = new \ReflectionMethod( $controller, $action );
						$method->invokeArgs( $controllerObj, $this->param );
				}
			}
		}
	}

	public function run(){
		$this->filterParam();
		if( is_callable( $this->getCallable() ) ){
			call_user_func_array($this->getCallable(), $this->param);
		}
		if($this->getController()){
			$this->runController();
		}
	}
}

?>