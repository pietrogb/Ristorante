<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    inizio_pagina("Ma certo! Ristorante");
    $conn=dbConnect();
    $login=$_SESSION['logged'];
    $query="select Id from Utenti where Login='$login'";
    $ris=mysql_query($query,$conn);
    $ar=(mysql_fetch_array($ris));
    $id=$ar[0];
    $query="select c.Id, Nome, c.Cognome, c.DataReg from Clienti c Natural Join Utenti u where u.Livello=2";
    $result=mysql_query($query,$conn)
      or die("Query fallita".mysql_error($conn));
    $ar=(mysql_fetch_array($ris));
    $head=array('Id', 'Nome', 'Cognome', 'Data Registrazione');
    subtitle("Visualizza membri del personale");
    $num_righe=mysql_num_rows($result);
    if(!$num_righe)
      echo"<p>Non ci sono entry nella tabella del Personale</p>";
    else{
      table_start($head);
      while($row=mysql_fetch_assoc($result))
	echo_row($row);
      table_end();
      };
    back("operazioni.php");
    page_end();
  }
  else header("Location: home.php");
?>