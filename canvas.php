<?php 

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
	<link rel="stylesheet" type="text/css" href="css/questions.css" />

    <style type="text/css">
      body {
        padding-top: 40px;
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
		      <a class="brand" href="index.html">Qivi<sup><font color="#10A4DB">GH</sup>&raquo;</font> <?php echo $_GET['repo']; ?></a>
		      <ul class = "nav pull-left">
		      	<li>
		      	  <button id="btn_question" class="btn btn-info">Questions</button>
		      	</li>
		      </ul>


		        <ul class="nav pull-right">
		          <li><a href="logout.php">Logout</a></li>
		        </ul>
		   
		    </div><!-- /.container -->
		  </div><!-- /.navbar-inner -->
	</div><!-- /.navbar -->
   

<div id="container" class="container">
     
	<div id="leftpane">
	</div>
   	
   	<!--This will be the right pane-->
   	<div id="main-container"  ondragover="allowDrop(event)">
	</div>

</div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

   <script type="text/javascript">
   var out = false;
   $('#btn_question').click(function(){
	   	if(out!=true){
	   		$('#leftpane').css('marginLeft',"0px");
			//$('div.container').css('left',"+=300");
			out=true;
		}
		else{
	   		$('#leftpane').css('marginLeft',"-300px");
			//$('div.container').css('left',"-=300");
			out=false;
		}
   });

   </script>
  </body>