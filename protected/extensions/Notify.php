<?php
class Notify {
	private $ch;
	function __construct(){
		$this->ch = curl_init();
		//This SHOULD NOT CHANGE BASED ON ENVIRONMENT unless we place our Node.JS server somewhere other than localhost.
		curl_setopt($this->ch,CURLOPT_URL, "http://localhost:9091/emit.js");
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch,CURLOPT_POST, 1);
	}
	
	/*
		Possible constraints:
		url  : JS regex (Via string.match())
		user : User ID(s). Either string, number, or array.
		*****************************
		PARAMETERS ARE OR'D TOGETHER.
		*****************************
	*/
	public function emit($event, $data, $constraints = array()){
	
		if (is_array($data)){
			$data = json_encode($data);
		}
		
		$ext = "";
		foreach ($constraints as $key => $value){
			$ext .= "&";
			if (is_string($value) || is_numeric($value)){
				$ext .= $key."=[".$value."]";
			}else if (is_array($value)){
				$ext .= $key."=".json_encode($value);
			}
		}
		
		curl_setopt($this->ch,CURLOPT_POSTFIELDS, "handler=".$event."&broadcast=".$data.$ext);
		curl_exec($this->ch);
		return $this;
	}
}