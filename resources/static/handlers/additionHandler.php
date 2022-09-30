<?php

$email = htmlspecialchars($_POST['email']);
$name = htmlspecialchars($_POST['name']);
$gender = intval(htmlspecialchars($_POST['gender']));
$status = intval(htmlspecialchars($_POST['status']));

$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die('Unable to create socket');

$result = socket_connect($sock, '127.0.0.1', 8080) or die('Unable to bind socket');

$method = $_POST['edit'] == 0 ? 'POST' : 'PUT';

$message = "$method / HTTP1.1\r\n\r\n{\"email\": \"$email\", \"name\": \"$name\", \"gender\": \"$gender\", \"status\": \"$status\"}";
socket_write($sock, $message, strlen($message));
$rawData = socket_read($sock, 1024);
socket_close($sock);
header('Location: http://localhost/index.php');
die();
