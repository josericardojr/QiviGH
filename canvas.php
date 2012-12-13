<?php 
include 'Questions.php';
include 'treeRecursive.php';

session_start();

$start = str_replace('-', '/', substr($_GET['created_at'], 0,-10));

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
    <script src="js/d3.v2.js"></script>
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
    <link href="css/tree.css" rel="stylesheet">


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
   

	<div id="container" class="container" style="width:180%; height:100%;">
		<div id="leftpane">
			<?php getQuestions(); ?>
		</div> 	
	   	<!--This will be the right pane-->
	   	<div id="rightpane"  ondragover="allowDrop(event)">
			<div id="panel" style="width:120%; height:100%;" ondrop="drop(event)" ondragover="allowDrop(event)">
				    		<div id="row" class="row">
					    		<div class="well">


                    <div id="crumbs" class="pull-left breadcrumb">
                        <div id="content"></div>
                    </div>


					                <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
					                  <i class="icon-calendar icon-large"></i>
					                  <span></span> <b class="caret" style="margin-top: 8px"></b>
					                </div>


					                	
					            </div>
				    		</div> 

				    		<div id ="panel_content" style="width:100%; height:100%;">
					    		<div id="chart" style="width:100%; height:100%;"></div>
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


<!--The following code is for the tree-->
<script type="text/javascript">
      var margin = {top: 20, right: 120, bottom: 20, left: 120},
          width = 1500 - margin.right - margin.left,
          height = 1200 - margin.top - margin.bottom,
          i = 0,
          duration = 500,
          root;

      var tree = d3.layout.tree()
          .size([height, width]);

      var diagonal = d3.svg.diagonal()
          .projection(function(d) { return [d.y, d.x]; });

      var vis = d3.select("#chart").append("svg")
          .attr("width", width + margin.right + margin.left+500)
          .attr("height", height + margin.top + margin.bottom)
        .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


      var data = <? theTree($_GET['repo']); ?>



      $(document).ready(function(){
      json =data;
        root = json;
        root.x0 = height / 2;
        root.y0 = 0;

        function collapse(d) {
          if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
          }
        }

        root.children.forEach(collapse);
        update(root);

      });

      function update(source) {

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse();

        // Normalize for fixed-depth.
        nodes.forEach(function(d) { d.y = d.depth * 180; });

        // Update the nodes…
        var node = vis.selectAll("g.node")
            .data(nodes, function(d) { return d.id || (d.id = ++i); });

        // Enter any new nodes at the parent's previous position.
        var nodeEnter = node.enter().append("g")
        	  .attr("id",function(d){return d.name;})
            .attr("class", "node")
            .attr("type",function(d){return d.type;})
            .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
            .on("click", click);

        nodeEnter.append("circle")
            .attr("id",function(d){return d.name;})
            .attr("type",function(d){return d.type;})
            .attr("r", 1e-6)
            .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

        nodeEnter.append("text")
        	  .attr("id",function(d){return d.name;})
            .attr("type",function(d){return d.type;})
            .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
            .attr("dy", ".35em")
            .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
            .text(function(d) { return d.name; })
            .style("fill-opacity", 1e-6);

        // Transition nodes to their new position.
        var nodeUpdate = node.transition()
            .duration(duration)
            .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

        nodeUpdate.select("circle")
            .attr("r", 4.5)
            .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

        nodeUpdate.select("text")
            .style("fill-opacity", 1);

        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
            .duration(duration)
            .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
            .remove();

        nodeExit.select("circle")
            .attr("r", 1e-6);

        nodeExit.select("text")
            .style("fill-opacity", 1e-6);

        // Update the links…
        var link = vis.selectAll("path.link")
            .data(tree.links(nodes), function(d) { return d.target.id; });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("d", function(d) {
              var o = {x: source.x0, y: source.y0};
              return diagonal({source: o, target: o});
            });

        // Transition links to their new position.
        link.transition()
            .duration(duration)
            .attr("d", diagonal);

        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
            .duration(duration)
            .attr("d", function(d) {
              var o = {x: source.x, y: source.y};
              return diagonal({source: o, target: o});
            })
            .remove();

        // Stash the old positions for transition.
        nodes.forEach(function(d) {
          d.x0 = d.x;
          d.y0 = d.y;
        });
      }

      // Toggle children on click.
      function click(d) {
        if (d.children) {
          d._children = d.children;
          d.children = null;
        } else {
          d.children = d._children;
          d._children = null;
        }
        update(d);
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
