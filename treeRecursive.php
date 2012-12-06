<?php 

session_start();

function theTree($repo){
	getUserCommits($repo);
}

function getUserCommits($repo){
	$commits_uri = 'https://api.github.com/repos/'.$_SESSION['login'].'/'.$repo.'/commits?'.$_SESSION['token'];
	$commits_response = file_get_contents($commits_uri);
	$_SESSION['commits'] = json_decode($commits_response,true); 



	$mylast = $_SESSION['commits'][1]['sha'];
	$myrecent = $_SESSION['commits'][0]['sha'];

	
	$tree_uri = $_SESSION['commits'][0]['commit']['tree']['url'].'?'.$_SESSION['token'];
	

	  getTree($tree_uri,$_GET['repo']);
}

function getTree($uri, $repo){

	$tree_response = file_get_contents($uri.'&recursive=1');
	$res = json_decode($tree_response,true);

	$output = '{ "name":"'.$repo.'", "children":[';
	$leadingComp=0;
	for($i=0;$i< count($res['tree']);$i++){


			if($res['tree'][$i]['type'] == 'blob'){

				if($i!=0)
					$output = $output.',{"name" : "'.$res['tree'][$i]['path'].'", "size": "'.$res['tree'][$i]['size'].'"}';
				else
					$output = $output.'{"name" : "'.$res['tree'][$i]['path'].'", "size": "'.$res['tree'][$i]['size'].'"}';
				$leadingComp++;
			}else{
				$val = subTree($res,$i,$leadingComp);
				$i=$i+$val['count'];
				$output = $output.$val['string'].'}';




			}
	}



	echo $output.']}';
}

function subTree($root,$begin, $leadingComp){


	$val = array('count' => 0);$j=0;

	if($begin!=0){
		if($leadingComp > 0)
			$store = ',{"name" : "'.$root['tree'][$begin]['path'].'", "children":[';
		else
			$store = '{"name" : "'.$root['tree'][$begin]['path'].'", "children":[';

	}
	else{	
			$store = '{"name" : "'.$root['tree'][$begin]['path'].'", "children":[';
	}
		

	for($i=$begin+1;$i<count($root['tree']);$i++){

		if($root['tree'][$i]['type']=='blob'){
			$pos = strpos($root['tree'][$i]['path'], $root['tree'][$begin]['path']);
			if($pos === false){
				$val['count'] = $j;
				$val['string'] = $store.']';
				return $val;
			}else{

				if(	($j != 0) ){
					$store = $store.',{"name" : "'.$root['tree'][$i]['path'].'", "size": "'.$root['tree'][$i]['size'].'"}';
				}
				else
					$store = $store.'{"name" : "'.$root['tree'][$i]['path'].'", "size": "'.$root['tree'][$i]['size'].'"}';

			}

		}else{

			$pos = strpos($root['tree'][$i]['path'], $root['tree'][$begin]['path']);
			if($pos === false){
				//Not subfolder
				$val['count'] = $j;
				$val['string'] = $store.']';

				return $val;
			}else{
				//is subfolder
				$v = subTree($root,$i,$j);
				$i= $i+$v['count']; $j= $j+$v['count'];	
				$store = $store.$v['string'].'}';

			}			

		}
		$j = $j+1;
	}

	$val['count'] = $j;
	$val['string'] = $store.']';

	return $val;
}


?>
					    		