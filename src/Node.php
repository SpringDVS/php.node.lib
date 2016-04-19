<?php

namespace SpringDvs;

class Node {
	private $springName;
	private $hostName;
	private $address;
	private $service;
	private $state;
	private $types;
	
	private $resource;
	
	public function __construct($spring, $host, $address, $service, $state, $types) {
		
		if( ($pos = strpos($host, "/")) !== false) {
			$this->hostName = substr($host, 0, $pos);
			$this->resource = substr($host, $pos+1);
		} else {
			$this->hostName = $host;
			$this->resource = "";
		}
		$this->springName = $spring;
		$this->address = $address;
		$this->service = $service;
		$this->state = $state;
		$this->types = $types;
	}

	/**
	 * Construct a node from a node-string format
	 * 
	 * @param String $nodestr The node string to use
	 * @return \SpringDvs\Node|boolean Filled out node otherwise false on error
	 */
	public static function from_nodestring($nodestr) {
		$atoms = explode(',', $nodestr);
		if(count($atoms) != 3 && count($atoms) != 4) {
			return false;
		}
		$address = array_map('intval', explode('.', $atoms[2]));
		return new Node($atoms[0], $atoms[1], $address, DvspService::unspecified, DvspNodeState::unspecified, DvspNodeType::undefined);
	}
	
	/**
	 * Construct a node with just the Springname
	 * 
	 * @param String $springname The springname to use
	 * @return \SpringDvs\Node The filled out node
	 */
	public static function from_springname($springname) {
		return new Node($springname, "", array(), DvspService::unspecified, DvspNodeState::unspecified, DvspNodeType::undefined);
	}

	/**
	 * Get the Springnanme
	 * @return String
	 */
	public function springname() {
		return $this->springName;
	}
	
	/**
	 * Get the Hostname
	 * @return String
	 */
	public function hostname() {
		return $this->hostName;
	}
	
	/**
	 * Get the address
	 * @return Array
	 */
	public function address() {
		return $this->address;
	}
	
	/**
	 * Get the service protocol
	 * @return SpringDvs\DvspService
	 */
	public function service() {
		return $this->service;
	}
	
	/**
	 * Get the current state
	 * @return SpringDvs\DvspNodeState
	 */
	public function state() {
		return $this->state;
	}
	
	/**
	 * Get types of roles the node performs
	 * 
	 * The roles are XORed into a bitfield
	 * 
	 * @return Bitfield
	 */
	public function types() {
		return $this->types;
	}

	/**
	 * Get the resource handle for accessing the node
	 * @return String
	 */
	public function resource() {
		return $this->resource;
	}
	
	/**
	 * Update the service protocol of the node
	 * @param SpringDvs\DvspService $service
	 */
	public function updateService($service) {
		$this->service = $service;
	}
	
	/**
	 * Update the types of roles of the node
	 * 
	 * XOR the roles into a bitfield
	 * 
	 * @param Bitfield $types
	 */
	public function updateTypes($types) {
		$this->types = $types;
	}

	/**
	 * Update the state of the node
	 * @param SpringDvs\DvspNodeState $state
	 */	
	public function updateState($state) {
		$this->state = $state;
	}
	
	/**
	 * Generate a node-string format string of node
	 * @return String
	 */
	public function to_node_string() {
		return $this->springName . ',' . $this->hostName . ',' . $this->address;
	}
	
	/**
	 * Generate a node-register format string of node
	 * @return String
	 */
	public function to_node_register() {
		return $this->springName . ',' . $this->to_host_resource();
	}
	
	/**
	 * Generate a `hostname/resource` format for string of node
	 * @return String
	 */
	public function to_host_resource() {
		if(strlen($this->resource) == 0) {
			return $this->hostName;
		}
		
		return $this->hostName . '/' . $this->resource;
	}
}