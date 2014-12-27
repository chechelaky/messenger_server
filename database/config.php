<?php

define ('MESSENGER_DB_NAME', 'messenger');
define ('MESSENGER_DB_HOST', '127.0.0.1');
define ('MESSENGER_DB_USER', 'jeanmi');
define ('MESSENGER_DB_PASSWORD', 'test');

return array
(
	'dsn' => 'mysql:dbname='.MESSENGER_DB_NAME.';host='.MESSENGER_DB_HOST,
	'username' => MESSENGER_DB_USER,
	'password' => MESSENGER_DB_PASSWORD,
	'options' => array
	(
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
	)
);
