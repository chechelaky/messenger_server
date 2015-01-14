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

var_dump($parameters);
//$user = $db->find('User', 'user', 'token = :token', $parameters);
$contact = $db->find('User', 'user', 'email = :contact', $parameters);

if ( $contact !== false) {
	echo 'hehe';
	$contactValid = new Contact(1, $user->id, $contact->id);
	insertContact($contact, 'contact');
	$json = array(
			'error' => false,
	);
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);