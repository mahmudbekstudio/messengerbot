<?php
class Model extends Instance {

	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

}