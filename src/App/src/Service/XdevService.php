<?php 

namespace App\Service;

class XdevService {
	
	private $dbSession;
	private $config;
	
	public function __construct($config) 
	{
		$this->config = $config;	
	}
	
	public function getSession()
	{
		if(!$this->dbSession) {
			$connectionString = "mysqlx://".$this->config['username'].":".$this->config['password']."@".$this->config["host"];
			$this->dbSession = \mysql_xdevapi\getSession($connectionString);
		}
	}
	
	public function __call($method, $params) {
		$this->getSession();
		return call_user_func_array(array($this->dbSession, $method), $params);
	}
}