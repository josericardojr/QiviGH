<?php

session_start();

$_SESSION=array();
session_destroy();

unset($_SESSION['loggedIn']);

 header( 'Location: http://localhost:8888/QiviGH/index.html' ) ;

?>