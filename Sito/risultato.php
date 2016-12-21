<?php
  require("libreria.php");
  $login=authenticate();
  if (isset($_SESSION['logged']))
    inizio_pagina("Ma certo! Ristorante");
  else{
    page_start("Ma certo! Ristorante","#F0E0B2");
    echo"<table border='0' bgcolor='#FFFF66' cellpadding='10' align='center' height='42'>";
    echo"<tr><td width='1600' align='right' >Benvenuto <b> Ospite! </b>";
    echo"</td></tr></table>";
    echo"</div>";
  }
  $conn=dbConnect();
  
  $i=$_GET["B1"];
  //sara` direttamente la funzione a stampare cosa si sta guardando.
  
  if($i=="0"){
    //echo"<h2>Menù</h2>";
    menu($conn);
  }
  else{
    result($conn,$i);
  }
  if (isset($_SESSION['logged']))
    back("operazioni.php");
  else
    back_home();
  page_end();
?>