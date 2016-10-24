<?php
namespace RedCat\Route;
use RedCat\Strategy\Di;
class FrontController implements \ArrayAccess{
	protected $router;
	protected $request;
	protected $di;
	protected $uri;
	function __construct(Router $router, Request $request, Di $di=null){
		$this->router = $router;
		$this->request = $request;
		$this->di = $di;
	}
	function getRoutes(){
		return $this->router->getRoutes();
	}
	function getGroups(){
		return $this->router->getGroups();
	}
	function find($uri,$server=null){
		return $this->router->find($uri,$server);
	}
	function map($map,$index=0,$prepend=false,$group=null,$continue=false){
		return $this->router->map($map,$index,$prepend,$group,$continue);
	}
	function append($match,$route=null,$index=0,$group=null,$continue=false){
		return $this->router->append($match,$route,$index,$group,$continue);
	}
	function prepend($match,$route=null,$index=0,$group=null,$continue=false){
		return $this->router->prepend($match,$route,$index,$group,$continue);
	}
	function group($group=null,$callback=null,$prepend=false){
		return $this->router->group($group,$callback,$prepend);
	}
	function getUri(){
		return $this->uri;
	}
	function run($uri,$domain=null){
		$uri = ltrim($uri,'/');
		$this->uri = $uri;
		if($this->router->find($uri,$domain)){
			$this->router->display();
			return true;
		}
	}
	function offsetSet($k,$v){
		$this->router->offsetSet($k,$v);
	}
	function offsetGet($k){
		$this->router->offsetGet($k);
	}
	function offsetExists($k){
		$this->router->offsetExists($k);
	}
	function offsetUnset($k){
		$this->router->offsetUnset($k);
	}
	function runFromGlobals(){
		if(isset($_SERVER['REDCAT_URI'])){
			$s = strlen($_SERVER['REDCAT_URI'])-1;
			$p = strpos($_SERVER['REQUEST_URI'],'?');
			if($p===false)
				$path = substr($_SERVER['REQUEST_URI'],$s);
			else
				$path = substr($_SERVER['REQUEST_URI'],$s,$p-$s);
			$path = urldecode($path);
		}
		else{
			$path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
		}
		return $this->run($path,$_SERVER['SERVER_NAME']);
	}
	function __invoke($uri,$domain=null){
		return $this->run($uri,$domain);
	}
}