<?php
require_once('database/db.php');
require_once('model/contact.php');
require_once('model/message.php');
require_once('model/user.php');

$parameters = array(
	':token' => null
);

foreach ( $_GET as $key => $value ) {
	$parameters[":$key"] = $value;
}

$json = array(
	'error' => true
);

$config = require_once('database/config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

$user = $db->find('User', 'user', 'token = :token', $parameters);

if ( $user !== false ) {
	$user->id = (int) $user->id;

	$contacts = $db->search('Contact', 'contact', 'initiator != :id && contact !=id', array(':id' => $user->id));

	foreach ( $contacts as $contact ) {
		$contact->contact = $db->find('User', 'user', 'id = :id', array(':id' => $contact->contact));

		unset($contact->contact->password);
		unset($contact->contact->token);
		$contact->contact->id = (int) $contact->contact->id;
		$contact->id = (int) $contact->contact->id;

		unset($contact->contact->id);
		unset($contact->initiator);
	}
	$json = array(
		'error' => false,
		'contacts' => $contacts
	);
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);