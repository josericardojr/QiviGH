<?php 
include 'Questions.php';

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
		      <a class="brand" href="profile.php">Qivi<sup><font color="#10A4DB">GH</sup>&raquo;</font> <?php echo $_GET['repo']; ?></a>
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
		<?php getQuestions(); ?>
	</div>
   	
   	<!--This will be the right pane-->
   	<div id="rightpane"  ondragover="allowDrop(event)">

<div id="panel" style="width:100%; height:100%;" ondrop="drop(event)" ondragover="allowDrop(event)">

    		The content

</div>

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

   <!--The following script is for drop events -->
   <script type="text/javascript">
		function allowDrop(ev)
		{
			ev.preventDefault();
		}
		function drag(ev)
		{
			ev.dataTransfer.setData("Text",ev.target.id);
		}
		function drop(ev)
		{
			ev.preventDefault();
			var data=ev.dataTransfer.getData("Text");
			alert("source " +data+ " target "+ev.target.id);
			if(ev.target.id.length!=0){}
			//window.location.href=data+".php?from="+from+"&to="+to+"&on="+ev.target.id;
		}
		function Canvasdrop(ev){
			ev.preventDefault();
			var data=ev.dataTransfer.getData("Text");
			var r = document.getElementById("myModel");
			for(var i=0;i<glo.length;i++){				
				var temp = glo[i];
				if(temp.hit((ev.x-r.offsetLeft+window.pageXOffset) ,(ev.y-r.offsetTop+window.pageYOffset)))
						alert("source:"+data+" target:"+temp.getName());
		
			}
		}
	</script>
  </body>