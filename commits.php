<?php 

session_start();

/*$start = str_replace('-', '/', substr($_GET['created_at'], 0,-10));*/

getUserCommits();

function getUserCommits(){
	$commits_uri = 'https://api.github.com/repos/'.$_SESSION['login'].'/'.$_GET['repo'].'/commits?'.$_SESSION['token'];
	$commits_response = file_get_contents($commits_uri);
	$_SESSION['commits'] = json_decode($commits_response,true); 



	$mylast = $_SESSION['commits'][1]['sha'];
	$myrecent = $_SESSION['commits'][0]['sha'];

	$compare_uri = 'https://api.github.com/repos/'.$_SESSION['login'].'/'.$_GET['repo'].'/compare/'.$mylast.'...'.$myrecent.'?'.$_SESSION['token'];
	$compare_response = file_get_contents($compare_uri);
	echo $compare_response;
/*	$_SESSION['compare'] = json_decode($compare_response,true);
	$_SESSION['changed'] = array();
	foreach ($_SESSION['compare']['files'] as $key => $value) {
		$_SESSION['changed'][] = $_SESSION['compare']['files'][$key]['filename'];	
	}*/

}
?>