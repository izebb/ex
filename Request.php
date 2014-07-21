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
 namespace Ex\Http;

class Request
{
	protected $methods = array();
	protected $server;
	protected $headers;

	public function __construct( \Ex\ServerEnv $server , \Ex\Http\Headers $headers)
	{	
		$this->headers = $headers;
		$this->server = $server;
		$this->methods = $server['accept_methods'];
	}

	public function getHttpMethods()
	{
		return $this->methods;
	}
	public function getRequestMethod()
	{
		return $this->server['request_method'];
	}

	public function isMethod($method )
	{	
		$suffix = 'method_';
		return $this->getRequestMethod() == $this->methods[$suffix.$method];
	}

	public function isPost()
	{
		return $this->isMethod('post');
	}

	public function isGet()
	{
		return $this->isMethod('get');
	}

	public function isPut()
	{
		return $this->isMethod('put');
	}

	public function isDelete()
	{
		return $this->isMethod('delete');
	}

	public function get( $key=null, $value=null )
	{
		if( $this->isGet() ){
			if( function_exists('mb_parse_str') )
			{
				mb_parse_str( $this->server['query'], $result );
			}else
			{
				parse_str(  $this->server['query'], $result  );
			}
			if( $key ){
				if( !$value ){
					return isset( $result[$key] ) ? $result[$key]:null;
				}else{
					if($result[$key]){
						array_merge( array( $result[$key] ), $value );
					}else{
						$result[$key] = $value;
					}
				}
			}
			return $result;
		}

	}

	/*public function isXHR()
	{
		if(isset( $this->headers->get('X_REQUESTED_WITH') ) ){
			return $this->headers['X_REQUESTED_WITH'] === 'XMLHttpRequest';
		}
		return false;
	}*/


	public function post( $key=null, $value=null )
	{
		if( $this->isPost() ){
			$postData = array();
			if( $this->server['form_input'] ){	
				$postData = $this->server['form_input'];
			}else{
				$postData = $_POST;
			}
			if( $key ){
				if( !$value ){
					return isset( $postData[$key] ) ? $postData[$key]:null;
				}else{
					array_shift($args);
					if($postData[$key]){
						array_merge( array( $postData[$key] ), $value );
					}else{
						$postData[$key] = $value;
					}
				}
			}
			return $postData;
		}
	}

	public  function put( $key=null, $value=null )
	{
		return $this->post($key, $value);
	}	

	public  function delete( $key=null, $value=null )
	{
		return $this->post($key, $value);
	}

}
?>