<?php
require_once('database/db.php');
require_once('model/contact.php');
require_once('model/message.php');
require_once('model/user.php');

$parameters = array(
	':token' => null,
	':contact' => null,
	':message' => null
);

foreach ( $_GET as $key => $value ) {
	$parameters[":$key"] = $value;
}

$idContact = $parameters[":contact"];

$userParameters = array(
	array_shift(array_keys($parameters)) => array_shift($parameters)
);

$contactParameters = array(
	array_shift(array_keys($parameters)) => array_shift($parameters)
);

$json = array(
	'error' => true
);

$config = require_once('database/config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

$user = $db->find('User', 'user', 'token = :token', $userParameters);

if ( $user !== false ) {
	$contact = $db->find('Contact', 'contact', 'contact = :contact', $contactParameters);

	if ( $contact != null ) {
		$message = new Message();
		$message->contact = $idContact;
		$message->message = $parameters[':message'];
		$message->date = date('Y-m-d H:i:s');
		$message->user = $user->id;
		$message->sent = true;

		$id = $db->insert($message, 'message');
		if ( $id !== false ) {
			$message->id = (int) $id;

			unset($message->id);
			unset($message->contact);
			unset($message->user);
			
			$json = array(
				'error' => false,
				'message' => $message
			);
		}
	}
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);