<?php
namespace Syph\Cache;

class CacheData {
	public  $data    = null;
	private $expires = 0;
	
	public function set($data, $duration = 0){
		$date = new \DateTime();
		$date->add(new \DateInterval('P'.$duration.'S'));
		$this->data = $data;
		$this->expires = $date;
	}
	
	public function get(){
		$date = new \DateTime();
		if ($this->data >= $date) return $this->data;
		else return null;
	}
	
}
