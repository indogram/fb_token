<?php
header('Origin: https://facebook.com');
define('API_SECRET', 'c1e620fa708a1d5696fb991c1bde5662');
define('BASE_URL', 'https://api.facebook.com/restserver.php');

function sign_creator(&$data){
	$sig = "";
	foreach($data as $key => $value){
		$sig .= "$key=$value";
	}
	$sig .= API_SECRET;
	$sig = md5($sig);
	return $data['sig'] = $sig;
}

function cURL($method = 'GET', $url = false, $data){
	$c = curl_init();
	$user_agents = array(
		"Mozilla/5.0 (iPhone; CPU iPhone OS 9_2_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13D15 Safari Line/5.9.5",
		"Mozilla/5.0 (iPhone; CPU iPhone OS 9_0_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13A452 Safari/601.1.46 Sleipnir/4.2.2m","Mozilla/5.0 (iPhone; CPU iPhone OS 9_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13E199 Safari/601.1","Mozilla/5.0 (iPod; CPU iPhone OS 9_2_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) CriOS/45.0.2454.89 Mobile/13D15 Safari/600.1.4","Mozilla/5.0 (iPhone; CPU iPhone OS 9_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13E198 Safari/601.1"
	);
	$useragent = $user_agents[array_rand($user_agents)];
	$opts = array(
		CURLOPT_URL => ($url ? $url : BASE_URL).($method == 'GET' ? '?'.http_build_query($data) : ''),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_USERAGENT => $useragent
	);
	if($method == 'POST'){
		$opts[CURLOPT_POST] = true;
		$opts[CURLOPT_POSTFIELDS] = $data;
	}
	curl_setopt_array($c, $opts);
	$d = curl_exec($c);
	curl_close($c);
	return $d;
}
echo"\n\e[37m? \e[33mInsert Email/Phone/ID : ";
$email = trim(fgets(STDIN));
echo"\n\e[37m? \e[33mInsert Password : ";
$passw = trim(fgets(STDIN));
echo"\n\e[37m? \e[33mType : ";
$typeg = trim(fgets(STDIN));
echo"\e[1;36m  | Try to login...\n\n";


$data = array(
	"api_key" => "3e7c78e35a76a9299309885393b02d97",
	"email" => $email,
	"format" => "JSON",
	"generate_session_cookies" => "1",
	"locale" => "id_ID",
	"method" => "auth.login",
	"password" => $passw,
	"return_ssl_resources" => "0",
	"v" => "1.0"
);

sign_creator($data);
if($typeg == '1'){
	$response = cURL('GET', false, $data);
	$token = json_decode($response,true);
	if($token["access_token"]){
		echo"\e[1;32m  | Success..\n";
		echo"\n\e[0m".$response."\n\n";
	}else{
		echo"\e[1;31m  | Error..\n";
		exit($response);
		echo"\n\n";
	}
}else{
	echo"\n\e[0m";
	exit(BASE_URL.http_build_query($data));
	echo"\n\n";
}
?>
