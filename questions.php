
<?php
include 'Constants.php';


function getQuestions(){
			//since we can't have arrays as constants in php, we have to make do with this
		$related = array(
											"c1_1" =>array("C1_2","D2","D4","D5"),
											"c1_2"=>array("C1_3","D2","D4","D5")
									);
		$refl = new ReflectionClass('CodeQ');
		$questions = $refl->getConstants();
		$out =  "<h4>Code</h4><ul>";
		foreach ($questions as $key => &$value) {
			$out =	$out.'<li><a href="#" id="'.$key.'" class="active" ondragstart="drag(event)"> '.$value.'</a></li>';
		}
		$out = $out. "</ul>";
		 
		 $refl = new ReflectionClass('DeveloperQ');
		$questions = $refl->getConstants();
		$out =$out. "<h4>Developer</h4><ul>";
		foreach ($questions as $key => &$value) {
			$out =$out.'<li><a href="#" id="'.$key.'" class="active" ondragstart="drag(event)"> '.$value.'</a></li>';
		}
		 $out =$out. "</ul>";
		 
		 
		 $refl = new ReflectionClass('BugQ');
		$questions = $refl->getConstants();
		$out =$out. "<h4>Bugs</h4><ul>";
		foreach ($questions as $key => &$value) {
			$out =$out.'<li><a href="#" id="'.$key.'" class="active" ondragstart="drag(event)"> '.$value.'</a></li>';
		}
		 $out =$out."</ul>";
		 echo $out;
}
?>

