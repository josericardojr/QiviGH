<?php 
include 'Questions.php';

session_start();


echo getTree($_GET['sha']);

function getTree($sha){

	$uri = 'https://api.github.com/repos/arelangi/maps/git/trees/'.$sha.'?'.$_SESSION['token'];

	//echo 'This is the name of the folder <b>'.$name.'</b><br>';
	$i=0;

	$children = '{"children" : [';

	$tree_response = file_get_contents($uri);
		
	$tree_response = json_decode($tree_response,true);

	foreach ($tree_response['tree'] as $key => $value) {
		
		if($tree_response['tree'][$key]['type'] =='blob'){
			
			if($i!=0)
				$children = $children. ',{ "type":"blob",  "name":"'.$tree_response['tree'][$key]['path'].'" , "url":"'.$tree_response['tree'][$key]['url'].'?'.$_SESSION['token'].'","size":'.$tree_response['tree'][$key]['size'].'}';
			else
				$children = $children. '{"type":"blob", "name":"'.$tree_response['tree'][$key]['path'].'" , "url":"'.$tree_response['tree'][$key]['url'].'?'.$_SESSION['token'].'","size":'.$tree_response['tree'][$key]['size'].'}';


		//	echo $tree_response['tree'][$key]['path'].'<br>';


		}else{

			if($i!=0)
				$children = $children. ',{ "type":"true",  "name":"'.$tree_response['tree'][$key]['path'].'" , "url":"'.$tree_response['tree'][$key]['url'].'?'.$_SESSION['token'].'"}';
			else
				$children = $children. '{"type":"true", "name":"'.$tree_response['tree'][$key]['path'].'" , "url":"'.$tree_response['tree'][$key]['url'].'?'.$_SESSION['token'].'"}';

/*
			$turl = $tree_response['tree'][$key]['url'].'?'.$_SESSION['token'];

			$drama = getTree($turl,$tree_response['tree'][$key]['path'] );

			if($i!=0)
				$children = $children.','.$drama;
			else
				$children = $children.$drama;*/
		}
		$i=$i+1;
	}

	$children = $children.']}';
	
	return $children;

}





?>
					    		