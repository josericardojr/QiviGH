<?php

session_start();

if(!$_SESSION["loggedIn"]){
	getAccesToken();
	$request = 'https://api.github.com/user?'.$_SESSION['token'];
	$response = file_get_contents($request);

	$data = json_decode($response,true);

	foreach ($data as $key => $value) {
		$_SESSION[$key] = $value;
	}
	getUserRepos();


	$_SESSION['loggedIn'] = true;
}



function getUserRepos(){
	$uri = 'https://api.github.com/user/repos?type=all&'.$_SESSION['token'];
	$response = file_get_contents($uri);
	$_SESSION['user_repos'] = json_decode($response,true); 
}

function getAccesToken(){
	$request = 'https://github.com/login/oauth/access_token?client_id=0e9b19a862aa8bb7893c&client_secret=b0a76b934c6d67da29cad377eb26de7b86ce3ab6&code='.$_GET['code'] ;
	$response = file_get_contents($request);
	$_SESSION['token'] = $response;
}

?>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>QiviGH</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="An interactive exploration tool for exploring Github projects">
    <meta name="author" content="Aditya Relangi">

    <!-- Le styles -->
    <link rel="stylesheet" href="css/bootstrap.css">

    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">

  </head>

  <body>
   <div class="navbar navbar-fixed-top">
		  <div class="navbar-inner">
		    <div class="container">
		      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </a>
		      <a class="brand" href="index.html">Qivi<sup><font color="#10A4DB">GH</font></sup></a>
		      <div class="nav-collapse">


		        <ul class="nav pull-right">
		          <li><a href="logout.php">Logout</a></li>
		        </ul>
		   
		      </div><!-- /.nav-collapse -->
		    </div><!-- /.container -->
		  </div><!-- /.navbar-inner -->
	</div><!-- /.navbar -->
   

<div class="container">
      <!-- Main hero unit for a primary marketing message or call to action -->
    <div class="hero-unit">
	    <div class="row">
				<div class="span3">
				
				 <?php echo '<a class="thumbnail" href="'.$_SESSION['html_url'].'"> <img alt="profile pic" src="http://www.gravatar.com/avatar/'.$_SESSION['gravatar_id'].'?s=200"> </a>'; ?>
				
				<?php echo '<center><a class="font_26" href="'.$_SESSION['html_url'].'">'.$_SESSION['login'].'</a></center>'; ?>
				</div>


				<div class="span7">
						<h4>Select one of your repositories to explore </h4>
					<?php 
							foreach ($_SESSION['user_repos'] as $key => $value) {
								echo '<a id='.$_SESSION['user_repos'][$key]['name'].'&created_at='.$_SESSION['user_repos'][$key]['created_at'].' class="btn btn-primary btn-large margin_profile">'.$_SESSION['user_repos'][$key]['name'].'</a>';
							} 
					?>


				</div>


		</div>

    </div>
</div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $('.margin_profile').on("click",function(event){
      	document.location.href = 'canvas.php?repo='+$(this).attr('id');
      });
    </script>
  </body>
</html>