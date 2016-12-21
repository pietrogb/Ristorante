<?php
  require("libreria.php");
  $login=authenticate();
  if (isset($_SESSION['logged']))
    inizio_pagina("Ma certo! Ristorante");
  else{
    page_start("Ma certo! Ristorante");
    echo"<table border='0' bgcolor='#FFFF66' cellpadding='10' align='center' height='42'>";
    echo"<tr><td width='1600' align='right' >Benvenuto <b> Ospite! </b>";
    echo"</td></tr></table>";
    echo"</div>";
  }
  subtitle("Orari del ristorante");
  Ul_start();
    Point("Lun:    11:30 - 22:30");
    Point("Mar:    11:30 - 22:30"); 
    Point("Mer:    11:30 - 22:30");
    Point("Gio:    11:30 - 22:30");
    Point("Ven:    11:30 - 00:00");
    Point("Sab:    11:30 - 00:00");
    Point('Dom:    11:30 - 21:30'); 
  Ul_end();
  if (isset($_SESSION['logged']))
    back("operazioni.php");
  else
    back_home();
  page_end();
?>