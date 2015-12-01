<?php
namespace Syph\Cache\Interfaces;

interface ICache {
	
	public function set($key, $value, $duration = 0);
	public function get($key);
	public function clear();
	public function delete($key);
	public function exists($key);

}
