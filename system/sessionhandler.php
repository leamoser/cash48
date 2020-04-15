<?php
if(isset($_SESSION['userid'])){
        $user = get_person_by_id($_SESSION['userid']);
        $user_id = $user['id'];
        $logged_in = true; //um prüfen zu können ob eingeloggt oder nicht
      } else{
        $logged_in = false;
      }
?>