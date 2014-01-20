<?php
class MongoEngine{

	private $conn;
	private $db;
	private $collection;
	private $connected = false;
	public $mongoClass;

	public function __construct($_username = null, $_password = null, $_host = null){
		if((!is_null($_username) && trim($_username) != "") && (!is_null($_password) && trim($_password) != "") && (!is_null($_host) && trim($_host) != "") ){
			if(extension_loaded(strtolower('mongo'))) {
				$mongo = (!class_exists('MongoClient'))? "Mongo" : "MongoClient";
				$this->mongoClass = $mongo;

				self::setConn(new $mongo("mongodb://{$_username}:{$_password}@{$_host}", array("db"=>"admin")));
		
				if(!is_null(self::getConn())) {
					$this->connected = true;
				} else {
					header('HTTP/1.0 400 Failed to connect');
					exit();	
				}
			} else {
				header('HTTP/1.0 400 Verify your driver');
				exit();
			}
		} else {
			header('HTTP/1.0 401 Proper credentials are needed');
			exit();
		}
	}

	private function setConn($_conn = null){
		$this->conn = $_conn;
	}

	private function getConn(){
		return $this->conn;
	}

	public function setDb($_db = null){
		$this->db = self::getConn()->selectDB($_db);
	}

	private function getDb(){
		return $this->db;
	}
	
	public function selectCollection($_collection = null) {
		self::setCollection($_collection);
		return self::getCollection();
	}

	public function setCollection($_collection = null){
		$this->collection = self::getDb()->selectCollection($_collection);
	}

	public function getCollection(){
		return $this->collection;
	}

	public function getStatus() {
		return $this->connected;
	}
}