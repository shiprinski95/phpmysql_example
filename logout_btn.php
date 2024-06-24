<?php
   session_start();
   unset($_SESSION['SESS_LOG']);

   // Resetarea variabilei de sesiune pentru numărul de vizitări
   $_SESSION['count'] = 0;

   // Redirecționare către pagina de login
   header("location: logare.php");
   exit;
?>
