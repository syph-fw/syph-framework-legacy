<?php

namespace Syph\Helpers;

//--fileatime — Obtém o último horário de acesso do arquivo
//filectime — Obtém o tempo de modificação do inode do arquivo
//--filegroup — Lê o grupo do arquivo
//fileinode — Lê o inode do arquivo
//--filemtime — Obtém o tempo de modificação do arquivo
//--fileowner — Lê o dono (owner) do arquivo
//--fileperms — Lê as permissões do arquivo
//--filesize — Lê o tamanho do arquivo
//filetype — Lê o tipo do arquivo		

class FileHelper {
	private $_fileRes		= null;
	
	private $name			= null;
	private $size			= null;
	private $lastAccess		= null;
	private $group			= null;
	private $lastModified	= null;
	private $owner			= null;
	private $permissions	= null;
	
	public function __construct($name) {
		$this->name = $name;
		
		$this->size = 0;
		if (file_exists($name)) {
			$this->size = filesize($name);
		}
	}
	
	public function __destruct() {
		$this->close();
	}
	
	protected function getResource(){
		if (empty($this->_fileRes)) $this->open('r');
		return $this->_fileRes;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getSize(){
		if (empty($this->size))
			$this->size = filesize($this->getName());
		return $this->size;
	}
	
	public function getLastAccess(){
		if (empty($this->lastAccess))
			 $this->lastAccess = fileatime($this->getName());
		return $this->lastAccess;
	}
	
	public function getGroup(){
		if (empty($this->group))
			$this->group = filegroup($this->getName());
		return $this->group;
	}
	
	public function getLastModified(){
		if (empty($this->lastModified))
			$this->lastModified = filegroup($this->getName());
		return $this->lastModified;
	}
	
	public function getOwner(){
		if (empty($this->owner))
			$this->owner = fileowner($this->getName());
		return $this->owner;
	}
	
	public function getPermissions(){
		if (empty($this->permissions))
			$this->permissions = fileowner($this->getName());
		return $this->lastModified;
	}
	
	public function open($mode){
		$this->_fileRes = fopen($this->getName(), $mode);
	}
	
	public function close(){
		if (!empty($this->_fileRes))
			fclose ($this->_fileRes);
	}
	
	public function content($start = 0, $end = null){
		if (is_null($end))
			$length = $this->size - $start;
		else
			$length = $end;
		if ($length <= 0) return '';
			
		fseek($this->getResource(), $start, SEEK_SET);
		return fread($this->getResource(), $length);
	}
	
	public function clear(){
		return ftruncate($this->getResource(), 0);
	}
	
	/**
	 * 
	 * @param string $content
	 * @return int
	 */
	public function append($content){
		fseek($this->getResource(), 0, SEEK_END);
		$b = fwrite($this->getResource(), $content);
		if ($b) {
			$this->size += $b;
			return true;
		}
		else 
			return false;
	}
	
	public function prepend($content){
		$content .= $this->content();
		$this->clear();
		$b = fwrite($this->getResource(), $content);
		
		if ($b) {
			$this->size += $b;
			return true;
		}
		else 
			return false;
	}
	
	public function find($regex, $flags = 0, $offset = 0){
		$content = $this->content();
		$matches = array();
		preg_match($regex, $content, $matches, $flags, $offset);
		return $matches;
	}
	
	public function replace($pattern, $replacement, $limit=-1, &$count=null){
		$content = $this->content();
		$this->clear();
		$this->append(preg_replace($pattern, $replacement, $content, $limit, $count));
	}
	
	//public function modify($name, $content);
	//public function delete($name);
	//public function copy($source, $destination);
	//public function move();
	
	
}
