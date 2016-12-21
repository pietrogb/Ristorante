<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged'])){
    $conn=dbConnect();
    //prendi tutti i vecchi dati, mettili nelle celle della form, fai i controlli e invia.
    $query= "select * from Clienti natural join Utenti u where u.Login='$login'";
    $ris=mysql_query($query, $conn);
    $old=mysql_fetch_assoc($ris);
    $nome = $old['Nome'];
    $cognome= $old['Cognome'];
    $email = $old['Email'];
    $telefono = $old['Telefono'];
    $Id=$old['Id'];
  
    if(isset($_POST['submit'])){
      $nome = $_POST['nome'];
      $cognome= $_POST['cognome'];
      $email = $_POST['email'];
      $telefono = $_POST['telefono'];
      
      if(!ctype_alpha($nome) || !ctype_alpha($cognome))
	$err="Nome e cognome devono essere alfanumerici e non vuoti";
	else if (isset($email) && ((strlen($telefono)>0) && !chkEmail($email)))
	  $err="E- mail non valida";
	  else if (isset($telefono) && ((strlen($telefono)>0) && !is_numeric($telefono)))
	    $err="il telefono deve essere numerico";
	    else{
	      //Inserisce il nuovo utente nella base di dati
	      $nome = mysql_real_escape_string($nome);
	      $cognome = mysql_real_escape_string($cognome);
	      $email = mysql_real_escape_string($email);
	      $telefono = mysql_real_escape_string($telefono);
	      $query="update Clienti set Nome='$nome', Cognome='$cognome', Email='$email', Telefono='$telefono' where Id='$Id';";
	      mysql_query($query,$conn);
	      echo"<b>Query riuscita correttamente</b><BR>";
	      $_SESSION['nome']=$nome;
	    };
    };  
  };

  inizio_pagina("Ma certo! Ristorante");
  form_start("POST","aggiornadati.php");
?>
  <b>Nome</b>	  
    <input type='text' name='nome' value='<?php echo $nome;?>'><br><br>
    <b>Cognome</b>
    <input type='text' name='cognome' value='<?php echo $cognome;?>'><br><br>
    <b>Telefono</b>
    <input type='text' name='telefono' value='<?php echo $telefono;?>'> - campo non obbligatorio - <br><br>
    <b>Mail</b>
    <input type='text' name='email' value='<?php echo $email;?>'> - campo non obbligatorio - <br><br>
<?php
  form_end();
  if(isset($err))
    echo"<BR><b>Errore: $err</b>";
  back("operazioni.php");
  page_end();