<?php
// Handle server -> rabbit login & registration requests here

session_start();

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

if (!isset($_POST))
{
	$msg = "NO POST MESSAGE SET, POLITELY FUCK OFF";
	echo json_encode($msg);
	exit(0);
}
$request = $_POST;
$response = "unsupported request type, politely FUCK OFF";
switch ($request["type"])
{
	// request.send("type=login&uname="+username+"&pword="+password)
	case "login":
		$loginRequest = array();
		$loginRequest['type'] = "login";
		$loginRequest['username'] = $request["uname"];
		$loginRequest['password'] = $request["pword"];
		$loginRequest['message'] = "login request";
		$response = $client->send_request($loginRequest);
		if ($response == 1)
		{
			$_SESSION['username'] = $request["uname"];
		}
		break;
	case "register":
		$regRequest = array();
		$regRequest['type'] = "register";
		$regRequest['username'] = $request["uname"];
		$regRequest['password'] = $request["pword"];
		$regRequest['message'] = "registration request";
		$response = $client->send_request($regRequest);
		break;
	case "setbio":
		$setBio = array();
		$setBio['type'] = "setbio";
		$setBio['username'] = $request["uname"];
		$setBio['newBio'] = $request["newbio"];
		$response = $client->send_request($setBio);
		break;
	case "getbio":
		$getBio = array();
		$getBio['type'] = "getbio";
		$getBio['username'] = $request["uname"];
		$response = $client->send_request($getBio);
		break;
	case "setcabinet":
		$setCab = array();
		$setCab['type'] = "setcabinet";
		$setCab['username'] = $request["uname"];
		$setCab['newCabinet'] = $request["newcabinet"];
		$response = $client->send_request($setCab);
		break;
	case "getcabinet":
		$getCab = array();
		$getCab['type'] = "getcabinet";
		$getCab['username'] = $request["uname"];
		$response = $client->send_request($getCab);
		break;	
	case "addlike":
		$addLike = array();
		$addLike['type'] = "addlike";
		$addLike['username'] = $request["uname"];
		$addLike['addLike'] = $request["addlike"];
		$response = $client->send_request($addLike);
		break;	
	case "getlikes":
		$getLikes = array();
		$getLikes['type'] = "getlikes";
		$getLikes['username'] = $request["uname"];
		$response = $client->send_request($getLikes);
		break;	
}

echo json_encode($response);

exit(0);
?>
