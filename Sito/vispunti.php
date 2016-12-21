<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $conn=dbConnect();
    
    inizio_pagina("Ma certo! Ristorante");
    subtitle("Visione punti clienti");
    $query= "select c.Id,c.Nome, c.Cognome, cf.TotPunti from Clienti c join CartaFedelta cf on c.Id=IdCliente order by cf.TotPunti desc;";
    $ris=mysql_query($query,$conn);
    $n=mysql_num_rows($ris);
    if($n>0){
      $h=array('Id','Nome','Cognome','Totale Punti');
      table_start($h);
      while($row= mysql_fetch_assoc($ris))
	echo_row($row);
      table_end();
    }
    else{
      echo"Non ci sono stati accessi al database";
    };
    back("operazioni.php");
  table_end();
  page_end();
  }
  else
    header("Location: home.php");
?>