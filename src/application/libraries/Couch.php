<?php
class Couch{
	public $instance;
	public $connection;
	function __construct($config){
		self::set_initialize_config($config);
		self::get_instance();
	}
	protected function get_instance(){
		if(!$this->instance){
			try{
				$this->instance = new Couch($this->connection['host'], $this->connection['username'], $this->connection['password'], $this->connection['bucket'], $this->connection['persistant']);
			} catch(CouchbaseException $exception){
				logger('error', __FUNCTION__, __CLASS__, $exception->getMessage());
				return false;
			}
		}
	}
	protected function set_initialize_config($connection){
		$this->connection = $connection;
	}
	function set($key, $data, $expired = 0){
		$this->instance->set($key, $value, $expired);
	}
	function get($key, $decode_arr = false){
		$result = $this->instance->get($key);
		if($decode_arr){
			$result = json_encode($result, true);
		}
		return $result;
	}
	function replace($key, $data, $expired = 0){
		$this->instance->replace($key, $value, $expired);
	}
	function update_multiple($data){
		$this->instance->setMulti($data, $expired);
	}
	function delete($key){
		$this->instance->delete($key);
	}
}