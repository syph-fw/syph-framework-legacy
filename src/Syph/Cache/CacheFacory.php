<?php

class CacheFacory {
	static public $cacheManager = array();
	
	static private function defaultConfig(){
		return array(
			'class'=>'FileCache',
		);
	}
	
	static protected function prepareConfig(array $config) {
	}
	
	static public function instance($config = null){
		if (empty($config)) $config = self::defaultConfig();
		if (is_string($config)) $config = array('class'=>$config);
		
		if (isset($cacheManager[$config['class']]))
			return $cacheManager[$config['class']];
		
		$cc = $cacheManager[$config['class']];
		$cacheManager[$config['class']] = new $cc();
		return $cacheManager[$config['class']];
	}
}
