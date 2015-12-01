<?php
namespace Syph\Cache;

use Syph\DependencyInjection\ServiceInterface;
use Syph\Cache\Interfaces\ICache;
use Syph\Helpers\FileHelper;
use Syph\Helpers\FilesHelper;

class FileCache implements ICache, ServiceInterface {
	
    public $keyPrefix = '';
    public $cachePath = '@appPath/shared/cache';
    public $cacheFileSuffix = '.cache';
    public $fileMode;
    public $dirMode = 0775;
	
	public function getName(){
		return 'cache';
	}

	protected function getCachePath(){
		$path = FilesHelper::normalizePath($this->cachePath);

		if (!file_exists($path))
			FilesHelper::createDirectory($path, $this->dirMode, true);
		return $path;
	}
	
	protected function makeFileName($key){
		return $this->getCachePath().'/'.$key.'.'.$this->cacheFileSuffix;
	}

	public function __construct() {
		$this->cachePath = FilesHelper::normalizePath($this->cachePath);
	}
	
	public function clear() {
		FilesHelper::removeDirectory($this->getCachePath());
	}

	public function delete($key) {
		if (file_exists($this->makeFileName($key)))
			FilesHelper::removeDirectory($this->makeFileName($key));
	}

	public function get($key) {
		$file = new FileHelper($this->makeFileName($key));
		return unserialize($file->content());
	}

	public function exists($key) {
		return file_exists($this->makeFileName($key));
	}

	public function set($key, $value, $duration = 0) {
		$file = new FileHelper($this->makeFileName($key));
		$file->open('w+');
		$file->clear();
		$file->append(serialize($value));
	}
}
