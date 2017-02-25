<?php
class Contact {
	public $id;
	public $initiator;
	public $contact;

	public $message;
	
	private function __construct(){
		$id = -1;
		$initiator = -1;
		$contact = -1;
		$message = -1;
	}

	function Contact ($id, $initiator, $contact) {
		$this->id = $id;
		$this->initiator = $initiator;
		$this->contact = $contact;
	}

	public function toDB() {
		$object = get_object_vars($this);
		unset($object['message']);
		return $object;
	}
}
