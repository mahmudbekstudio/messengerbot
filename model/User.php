<?php
class User extends Model {

	public function __construct($db) {
		parent::__construct($db);
	}

	public function getTableName() {
		return 'user';
	}

	public function getUser($userId, $messenger) {
		$list = $this->select(array('where' => "`user_id`='" . $userId . "' AND `messenger_type`='" . $messenger . "'"));
		return $list[0];
	}

	public function saveUserAction($userId, $userAction) {
		$this->update(array('user_action' => $userAction), "`user_id`='" . $userId . "'");
	}

}