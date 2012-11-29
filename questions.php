
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
		$out =  "<h2>Code</h2><ul>";
		foreach ($questions as $key => &$value) {
			$out =	$out.'<li><a href="#" id="'.$key.'" class="active" ondragstart="drag(event)"> '.$value.'</a></li>';
		}
		$out = $out. "</ul>";
		 
		 $refl = new ReflectionClass('DeveloperQ');
		$questions = $refl->getConstants();
		$out =$out. "<h2>Developer</h2><ul>";
		foreach ($questions as $key => &$value) {
			$out =$out.'<li><a href="#" id="'.$key.'" class="active" ondragstart="drag(event)"> '.$value.'</a></li>';
		}
		 $out =$out. "</ul>";
		 
		 
		 $refl = new ReflectionClass('BugQ');
		$questions = $refl->getConstants();
		$out =$out. "<h2>Bugs</h2><ul>";
		foreach ($questions as $key => &$value) {
			$out =$out.'<li><a href="#" id="'.$key.'" class="active" ondragstart="drag(event)"> '.$value.'</a></li>';
		}
		 $out =$out."</ul>";
		 echo $out;
}
?>

