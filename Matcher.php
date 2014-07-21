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

class Matcher
{
	/**
	* Pattern keys with associated regular expression
	* @var array
	*/
	private static $pattern = array(
		'any'=>'.',
        'num'=>'[0-9]+',
        'alpha'=>'[A-z]+',
        'alnum'=>'[a-zA-Z0-9]*',
	);

	/**
	* Paramter after any match coressponding to : prefix
	* @var array
	*/
	private $paramName = array();
	/**
	* Pattern of url
	* @var String
	*/
	private $urlPattern;
	/**
	* The requested string to be matched by pattern
	* @var String
	*/
	private $subject;

	

	public function __construct( $subject, $urlPattern )
	{
		$this->urlPattern = $urlPattern;
		$this->subject = $subject;
	}
	
	public function  __toString(){
		return '';
	}
	/**
	* Add pattern to $pattern
	* 
	* @param array $pattern
	* @return void
	*/
	public function registerPattern( Array $pattern )
	{
		Matcher::$pattern = array_merge( Matcher::$pattern, $pattern );
	}
	/**
	* Retrive pattern by key name
	* 
	* @param String $key
	* @return String
	*/
	public function getPattern( $key = NULL )
	{
		if(is_null($key) ){
			return Matcher::$pattern;
		}
		if( isset(Matcher::$pattern[$key]) ){
			return Matcher::$pattern[$key];
		}
		return;
	}

	/**
	* Creates Regular Expression from pattern
	* pattern are usually in the form <key>/<key2>
	* @param String $key
	* @return Regex
	*/
	public function build()
	{

		$patternRegex = '/(<(\w+(\|\w+)?)>)+/';
		$pattern = str_replace('/','\/', $this->urlPattern);
		$patternExpr = preg_replace_callback($patternRegex, function($match){
		    $match = str_replace( array('<','>'), array('',''), $match[1] );
		    //if it contains< ?|? >
		    if(strpos($match,'|')){
		        $matchSplit = explode('|', $match);
		        $matchSplit = array_map(function($match){
		            return Matcher::$pattern[$match];
		        }, $matchSplit);
		        $joined = '('.implode('|', $matchSplit).')';
        		$this->paramName[] = $joined;
		       return '('.implode('|', $matchSplit).')';
		    }
		    $match= Matcher::$pattern[$match];
		    $this->paramName[] = '('.$match.')';
		    return '('.$match.')';
		}, $pattern );
		return $patternExpr ;
	}
	/**
	* Test to see if it regex matches
	* @param String $key
	* @return Boolean
	*/
	public function test()
	{
		$regex = $this->build();
		return preg_match('/^'.$regex.'$/', $this->subject);
	}

	/**
	* Creates Regular Expression from pattern
	* pattern are usually in the form <key>/<key2>
	* @param String $key
	* @return Regex
	*/
	public function getParam()
	{
		preg_match('/'.implode('\/', $this->paramName).'/', $this->subject, $param);
		array_shift($param);
		return $param;
	}

	
}

?>