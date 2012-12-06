<?php 
include 'Questions.php';

session_start();

$start = str_replace('-', '/', substr($_GET['created_at'], 0,-10));

getUserCommits();

function getUserCommits(){
	$commits_uri = 'https://api.github.com/repos/'.$_SESSION['login'].'/'.$_GET['repo'].'/commits?'.$_SESSION['token'];
	$commits_response = file_get_contents($commits_uri);
	$_SESSION['commits'] = json_decode($commits_response,true); 



	$mylast = $_SESSION['commits'][1]['sha'];
	$myrecent = $_SESSION['commits'][0]['sha'];

	$compare_uri = 'https://api.github.com/repos/'.$_SESSION['login'].'/'.$_GET['repo'].'/compare/'.$mylast.'...'.$myrecent.'?'.$_SESSION['token'];
	$compare_response = file_get_contents($compare_uri);
	$_SESSION['compare'] = json_decode($compare_response,true);
	$_SESSION['changed'] = array();
	foreach ($_SESSION['compare']['files'] as $key => $value) {
		$_SESSION['changed'][] = $_SESSION['compare']['files'][$key]['filename'];	
	}

	$tree_uri = $_SESSION['commits'][0]['commit']['tree']['url'].'?'.$_SESSION['token'];
	$tree_response = file_get_contents($tree_uri);
	$_SESSION['tree'] = json_decode($tree_response,true);
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
	<link rel="stylesheet" type="text/css" media="all" href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" />	<link rel="stylesheet" type="text/css" href="css/questions.css" />
    <link rel="stylesheet" type="text/css" href="css/over.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker.css" />
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/date.js"></script>
    <script type="text/javascript" src="js/daterangepicker.js"></script>
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
		      	  <button id="btn_question" class="btn btn-info btn-small">Questions</button>
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
				    		<div id="row" class="row">
					    		<div class="well">

					    			<div id="crumbs" class="pull-left breadcrumb">
					    					<div id="content"></div>
					    			</div>

					                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
					                  <i class="icon-calendar icon-large"></i>
					                  <span></span> <b class="caret" style="margin-top: 8px"></b>
					                </div>
					                	
					            </div>
				    		</div> 

				    		<div id ="panel_content">
					    		<?php 
					    			$found = true;

					    		foreach ($_SESSION['tree']['tree'] as $key => $value) {

					    			foreach ($_SESSION['changed'] as $keyx => $valuex) {
					    				$pos = strpos($_SESSION['changed'][$keyx] , $_SESSION['tree']['tree'][$key]['path']);
											if($pos === false) {
												$found = false;
											}
											else {
											 $found=true;
											 break;
											}
					    			}


					    			if($found === true){
										if($_SESSION['tree']['tree'][$key]['type'] =='blob'){
						    				echo '<a id='.$_SESSION['tree']['tree'][$key]['path'].' class="btn btn-warning btn-small margin_profile">'.$_SESSION['tree']['tree'][$key]['path'].'</a>';
						    			}else{
						    				echo '<a id='.$_SESSION['tree']['tree'][$key]['path'].' onclick ="btn_click_catch(event);"  class="btn btn-warning btn-large margin_profile" href="'.$_SESSION['tree']['tree'][$key]['url'].'?'.$_SESSION['token'].'" >'.$_SESSION['tree']['tree'][$key]['path'].'</a>';
						    			}
					    			}else{
						    			if($_SESSION['tree']['tree'][$key]['type'] =='blob'){
						    				echo '<a id='.$_SESSION['tree']['tree'][$key]['path'].' class="btn btn-primary btn-small margin_profile">'.$_SESSION['tree']['tree'][$key]['path'].'</a>';
						    			}else{
						    				echo '<a id='.$_SESSION['tree']['tree'][$key]['path'].' onclick ="btn_click_catch(event);"  class="btn btn-primary btn-large margin_profile" href="'.$_SESSION['tree']['tree'][$key]['url'].'?'.$_SESSION['token'].'" >'.$_SESSION['tree']['tree'][$key]['path'].'</a>';
						    			}
					    			}
					    			

					    		}

					    		?>
				    		</div>

			</div>
		</div>
	</div>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>

<!--Prevent leftpane links from opening-->
<script type="text/javascript">
var ajaxCounter=0;var panelContentState = new Array(); var crumbsContentState = new Array();
	$("#leftpane a").click(function(event){
	  event.preventDefault();
	});
</script>

<!--Script for drill down-->
<script type="text/javascript">
var token = "<?php echo $_SESSION['token']; ?>";
	function btn_click_catch(event){
		event.preventDefault();
		$.getJSON(event.srcElement.href,function(data){
			var content ='<div id ="panel_content">';

			$.each(data.tree,function(key,value){	
				if(value.type=='blob'){
					content += '<a id="'+value.path+'" class="btn btn-primary btn-small margin_profile">'+value.path+'</a>';
				}else{
					content += '<a id="'+value.path+'" onclick ="btn_click_catch(event);" class="btn btn-primary btn-large margin_profile" href="'+value.url+'?'+token+'">'+value.path+'</a>';
				}
			});
			content +='</div>';
			crumbs(ajaxCounter);
			$('#panel_content').remove();
			$('#panel').append(content);

		});
	}
</script>

<!--The following script is for the button click slide in slide out-->
   <script type="text/javascript">
   var out = false; 
   $('#btn_question').click(function(){
	   	if(out!=true){
	   		$('#leftpane').css('marginLeft',"0px");
			$('#rightpane').css('marginLeft',"+=300");
			out=true;
		}
		else{
	   		$('#leftpane').css('marginLeft',"-300px");
			$('#rightpane').css('marginLeft',"-=300");
			out=false;
		}
   });

   </script>

   <!--The following script is for drop events -->
   <script type="text/javascript">
   var reqCounter=0;
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
			if(ev.target.id.length!=0){
				//We have a drop event on an element
				reqCounter++;
				crumbs(ajaxCounter); // This function will take care of the breadcrumbs

				//This is where we have to make an ajax call

			}
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

	<!--This is for the state saving -->
	<script type="text/javascript">
		function crumbClick(ev){
			ev.preventDefault();

			if(ev.srcElement.text < ajaxCounter)
			{
				panelContentState.splice(ev.srcElement.text+1,ajaxCounter);
				crumbsContentState.splice(ev.srcElement.text+1,ajaxCounter);
				ajaxCounter = ev.srcElement.text;
			}

			
			$('#content').remove();
			$('#crumbs').append('<div id="content">'+crumbsContentState[ev.srcElement.text]+'</div>');

			$('#panel_content').remove();
			$('#panel').append('<div id ="panel_content">'+panelContentState[ev.srcElement.text]+'</div>');

				
		}
	</script>



<!--This is the script for breadcrumbs generation-->
	<script type="text/javascript">
				function crumbs(data){
					var link='<div id="content">';
					for (var i = 0; i <=ajaxCounter; i++) {
						link += '<a onclick="crumbClick(event);" href="">'+i+'</a> <span class="divider">/</span>';
					};
					link +='</div>';
					panelContentState[ajaxCounter] = $('#panel_content').html();
					crumbsContentState[ajaxCounter] = $('#content').html();
					ajaxCounter++;
					$('#content').remove();
					$('#crumbs').append(link);
				}
	</script>

<!--The following code is for the date range picker -->
<script type="text/javascript">
               $(document).ready(function() {
               	var startx = "<? echo $start; ?>";
                  $('#reportrange').daterangepicker(
                     {
                        ranges: {
                           'Today': ['today', 'today'],
                           'Yesterday': ['yesterday', 'yesterday'],
                           'Last 7 Days': [Date.today().add({ days: -6 }), 'today'],
                           'Last 30 Days': [Date.today().add({ days: -29 }), 'today'],
                           'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                           'Last Month': [Date.today().moveToFirstDayOfMonth().add({ months: -1 }), Date.today().moveToFirstDayOfMonth().add({ days: -1 })]
                        },
                        opens: 'left',
                        format: 'MM/dd/yyyy',
                        separator: ' to ',
                        startDate: startx,
                        endDate: Date.today(),
                        minDate: startx,
                        maxDate: Date.today(),
                        locale: {
                            applyLabel: 'Submit',
                            fromLabel: 'From',
                            toLabel: 'To',
                            customRangeLabel: 'Custom Range',
                            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            firstDay: 1
                        },
                        showWeekNumbers: true,
                        buttonClasses: ['btn-danger']
                     }, 
                     function(start, end) {
                        $('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
                     }
                  );

                  //Set the initial state of the picker label
                  $('#reportrange span').html(Date.today().add({ days: -29 }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));

               });
    </script>
