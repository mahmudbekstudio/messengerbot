<?php
abstract class Model extends Instance {

	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	abstract public function getTableName();

	public function getError() {
		return $this->db->getError();
	}

	public function query($q) {
		return $this->db->query($q);
	}

	public function fetch($r = NULL, $type = '') {
		return $this->db->fetch($r, $type);
	}

	public function select($params) {
		return $this->db->select($this->getTableName(), $params);
	}

	public function getInsertId() {
		return $this->db->getInsertId();
	}

	public function insert($fields) {
		return $this->db->insert($this->getTableName(), $fields);
	}

	public function update($fields, $where = '') {
		$this->db->update($this->getTableName(), $fields, $where);
	}

	public function delete($where = '') {
		$this->db->delete($this->getTableName(), $where);
	}

}