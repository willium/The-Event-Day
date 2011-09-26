<?php
class Element {
	private $data = array();

	public function __set($name, $value) {
	    $this->data[$name] = $value;
	}
	
	public function __get($name) {
	    if (array_key_exists($name, $this->data)) {
	        return $this->data[$name];
	    }
	
	    $trace = debug_backtrace();
	    trigger_error(
	        'Undefined property via __get(): ' . $name .
	        ' in ' . $trace[0]['file'] .
	        ' on line ' . $trace[0]['line'],
	        E_USER_NOTICE);
	    return null;
	}
	
	public function getData() {
		return $this->data;
	}

	public function __toString() {
		return $this->html;
	}
	
	public function __isset($name) {
	    return isset($this->data[$name]);
	}
	
	public function __unset($name) {
	    unset($this->data[$name]);
	}
	
	public function __construct($values) {
		$inside = null;
		$attributes = null;
		foreach($values as $key=>$val) {
			if($key == "tag") $tag = $val;
			else if($key =="inside") $inside = $val;
			else $attributes .= "$key = \"$val\"";
		}
		$this->html = "<$tag"."$attributes>$inside</$tag>";
	}
}
?>