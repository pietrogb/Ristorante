<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged'])){
    if(isset($_POST['submit'])){
      
      $old=SHA1($_POST['oldpwd']);
      $pwd=SHA1($_POST['pwd']);
      $l=strlen($_POST['pwd']);
      $conferma=SHA1($_POST['conf']);
      $conn=dbConnect();
      
      $query= "select * from Utenti where Login='$login' and password='$old'";
      $ris=mysql_query($query, $conn);
      if(mysql_num_rows($ris)!=1)
	$err="La vecchia password è sbagliata";
      elseif($pwd!=$conferma) $err="La nuova password e la conferma devono essere uguali";
	elseif($l<8)
	  $err="La password dev'essere di almeno 8 caratteri";
	    else{
	      echo strlen($pwd);
	      $query="Update Utenti set password='$pwd' where Login='$login'";
	      mysql_query($query,$conn);
	      echo"<p><b>Password cambiata correttamente</b></p>";
	      }
      
      };
    inizio_pagina("Ma certo! Ristorante");
    form_start('POST','cambiapassword.php');
  echo<<<END
	Vecchia Password: <input type="password" name="oldpwd"> <BR>
	Nuova Password: <input type="password" name="pwd"> <BR>
	Conferma Password: <input type="password" name="conf"> <BR>
END;
    form_end();
      if(isset($err))
	echo"<BR><b>Errore: $err</b>";   
      back("operazioni.php"); 
      page_end();
  }
else
  header("Location: home.php");
  
?>