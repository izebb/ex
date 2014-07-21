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

class ServerEnv implements \ArrayAccess, \IteratorAggregate
{

	private $serverVars = array();

    public function __construct( $overwriteConfig = null ) {
    	if( $overwriteConfig ){
    		$this->serverVars = $overwriteConfig;
    	}else{
    		$this->serverVars['accept_methods'] = array(
    			'method_post'=> 'POST',
    			'method_put'=> 'PUT',
    			'method_get'=> 'GET',
    			'method_delete'=>'DELETE',
    			'method_head'=>'HEAD',
    			'method_option'=>'OPTION'
    		);
    		$this->serverVars['query'] = $_SERVER['QUERY_STRING'];
    		$rawInput = file_get_contents('php://input');
    		if($rawInput){
    			$rawInput =  urldecode($rawInput);
    			if(function_exists('mb_parse_str')){
    				mb_parse_str($rawInput, $input);
    			}else{
    				parse_str($rawInput, $input);
    			}
    			$this->serverVars['form_input'] = $input;
    		}
    		$this->serverVars['server_name'] = $_SERVER['SERVER_NAME'];
    		$this->serverVars['server_addrs'] = $_SERVER['SERVER_ADDR'];
    		$this->serverVars['server_port'] = $_SERVER['SERVER_PORT'];
    		$this->serverVars['server_scheme'] = $_SERVER['REQUEST_SCHEME'];
    		$this->serverVars['request_uri'] = $_SERVER['REQUEST_URI'];
    		$this->serverVars['request_method'] = $_SERVER['REQUEST_METHOD'];
    		$this->serverVars['server_protocol'] = $_SERVER['SERVER_PROTOCOL'];

    		$this->serverVars['redirect_uri'] = $_SERVER['REDIRECT_URL'];
    		$requestUri  = parse_url( $_SERVER['REQUEST_URI'] );
			$requestUri  = $requestUri ['path'];
				$scriptName  = $_SERVER['SCRIPT_NAME'];
			$directory = dirname($_SERVER['SCRIPT_NAME']);
			$scriptName  = substr($requestUri, strlen( $directory ) );
			$scriptName = trim($scriptName, '/');
    		$this->serverVars['pretty_uri'] = $scriptName;
    		$this->serverVars['base_dir'] = $directory;
    		$this->serverVars['base_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$directory;
    		$this->serverVars['http_request_headers'] = array();

    		foreach ($_SERVER as $key => $value) {
    			if(preg_match('/^HTTP_/', $key) ||  preg_match('/^HTTPS_/', $key) ){
    				$this->serverVars['http_request_headers'][$key] = $value;
    			}
    		}
    	}
     
    }
	/**
     * Array Access: Offset set
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->serverVars[] = $value;
        } else {
            $this->serverVars[$offset] = $value;
        }
    }
    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset) {
        return isset($this->serverVars[$offset]);
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset) {
        unset($this->serverVars[$offset]);
    }

    /**
     * Array Access: Offset get
     */
    public function offsetGet($offset) {
        return isset($this->serverVars[$offset]) ? $this->serverVars[$offset] : null;
    }

    /**
     * IteratorAggregate
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->serverVars);
    }

}
?>