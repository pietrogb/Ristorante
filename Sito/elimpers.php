<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $conn=dbConnect();
    inizio_pagina("Ma certo! Ristorante");
    subtitle("Cancellazione membri del personale");
    if(isset($_POST['submit'])){
      if(isset($_POST['Ids'])){
	$ids=$_POST['Ids'];
	
	foreach($ids as $d){
	  $query_el="update Utenti set Livello=1 where Id='$d'";
	  mysql_query($query_el,$conn) or err('impossibile eliminare Id=$d');
	  echo"L'utente con Id='$d' e` stato rimosso con successo dal personale<BR>";
	}
      }
      else subtitle("nessuna persona selezionata");
    }
    $login=$_SESSION['logged'];
    $query="select Id from Utenti where Login='$login'";
    $ris=mysql_query($query,$conn);
    $ar=(mysql_fetch_array($ris));
    $id=$ar[0];
    $query="select c.Id, Nome, c.Cognome, c.DataReg from Clienti c Natural Join Utenti u where u.Id!=1 and u.Livello=2";
    $result=mysql_query($query,$conn)
      or die("Query fallita".mysql_error($conn));
    $ar=(mysql_fetch_array($ris));
    $head=array('Id', 'Nome', 'Cognome', 'Data Registrazione', 'Scelta');
    $num_righe=mysql_num_rows($result);
    if(!$num_righe)
      echo"<p>Non ci sono altri membri appartenenti al personale</p>";
    else{
      form_start("POST","elimpers.php");
      table_start($head);
      while($row=mysql_fetch_assoc($result))
	echo_row_CK($row);
      table_end();
      form_end();
      };
      back("operazioni.php");
      page_end();
  }
  else
    header("Location: home.php");
  ?>