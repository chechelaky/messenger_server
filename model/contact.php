<?php
class Contact {
	public $id;
	public $initiator;
	public $contact;

	public $message;

	function Contact($id = null, $initiator = null, $contact = null){
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
