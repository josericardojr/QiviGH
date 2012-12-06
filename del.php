<?php

$pos = strpos('img/cutmypic.png','img');

if($pos === false) {
 echo 'string needle NOT found in haystack';
}
else {
 echo 'string needle found in haystack';
}

?>