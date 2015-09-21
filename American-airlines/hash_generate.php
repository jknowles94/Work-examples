<?php

 $user = 'aaexport';
 $pass = 'welovedata'; 

 $useroptions = ['cost' => 10,];
 $userhash    = password_hash($user, PASSWORD_BCRYPT, $useroptions);
 $pwoptions   = ['cost' => 10,];
 $passhash    = password_hash($pass, PASSWORD_BCRYPT, $pwoptions);

 echo $userhash;
 echo "<br />";
 echo $passhash;

 ?>