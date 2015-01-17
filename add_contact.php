<?php
require_once('database/db.php');
require_once('model/contact.php');
require_once('model/message.php');
require_once('model/user.php');

$parameters = array(
	':token' => null,
	':contact' => null
);

foreach ( $_GET as $key => $value ) {
	$parameters[":$key"] = $value;
}

$json = array(
	'error' => true
);

$config = require_once('database/config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

$email = array(
	':email' => $parameters[':contact']
);

$contactDb = $db->find('User', 'user', 'email = :email', $email);

if ( $contactDb !== false && $contactDb->token != $parameters[':token']) {
	$userParameters = array(
		array_shift(array_keys($parameters)) => array_shift($parameters)
	);

	$user = $db->find('User', 'user', 'token = :token', $userParameters);

	$paramContact = array(
		':initiator' => $user->id,
		':contact' => $contactDb->id
	);

	$contactTmp = $db->find('Contact', 'contact', 'initiator = :initiator AND contact = :contact', $paramContact);

	if( $contactTmp === false ) {
		$contact = new Contact();
		$contact->initiator = $user->id;
		$contact->contact = $contactDb->id;

		$id = $db->insert($contact, 'contact');

		$contact2 = new Contact();
		$contact2->initiator = $contactDb->id;
		$contact2->contact = $user->id;

		$id2 = $db->insert($contact2, 'contact');

		if ( $id !== false && $id2 !== false) {
			$json = array(
				'error' => false,
			);
		}
	}
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);