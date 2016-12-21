<?php
  require("libreria.php");
  page_start("Ma certo! Ristorante");

  if(isset($_POST['submit'])){
  
  //recupera i dati passati nel modulo
  $nickname = $_POST['nickname'];
  $password =$_POST['pass1'];
  $pass1 = $_POST['pass2'];
  $nome = $_POST['nome'];
  $cognome= $_POST['cognome'];
  $email = $_POST['email'];
  $telefono = $_POST['telefono'];
  
  // ora controlliamo che i campi siano stati tutti compilati
  if ( !ctype_alnum($nickname) || !ctype_alnum($password))
    $err="Login e password devono essere alfanumerici e non vuoti!";
  // controllo se il campo mail è stato scritto in maniera errata
  elseif ( $password != $pass1 )
    $err="Le password non corrispondono";
    elseif(strlen($password)<8)
      $err="La password dev'essere di almeno 8 caratteri";
      elseif(get_pwd($nickname))
	$err="nickname già utilizzato";
	elseif(!ctype_alpha($nome) || !ctype_alpha($cognome))
	$err="Nome e cognome devono essere alfanumerici e non vuoti";
	else if (isset($email) && ((strlen($telefono)>0) && !chkEmail($email)))
	  $err="E- mail non valida";
	  else if (isset($telefono) && ((strlen($telefono)>0) && !is_numeric($telefono)))
	    $err="il telefono deve essere numerico";
	      else{
		//Inserisce il nuovo utente nella base di dati
		$nickname = mysql_real_escape_string($nickname);
		$nome = mysql_real_escape_string($nome);
		$cognome = mysql_real_escape_string($cognome);
		$email = mysql_real_escape_string($email);
		$telefono = mysql_real_escape_string($telefono);
		$id=new_Client($nome, $cognome,$email,$telefono);
		$r=new_user($id,$nickname, $password);
	      };
  };

  table_start_NH("");
?>
      <tr>
	<td>
<?php  
  if(isset($err) || !isset($_POST['submit'])) {
?>    
      <form action="registrazione.php" syle="border=none" method='POST'>
	<fieldset syle="border=none">
	  <b>NickName</b>
	  <input type='text' name='nickname'><br><br>
	  <b>Password</b>
	  <input type='password' name='pass1'><br><br>
	  <b>Ripeti Password</b>
	  <input type='password' name='pass2'><br><br>
	  <b>Nome</b>	  
	  <input type='text' name='nome'><br><br>
	  <b>Cognome</b>
	  <input type='text' name='cognome'><br><br>
	  <b>Telefono</b>
	  <input type='text' name='telefono' value=""> - campo non obbligatorio - <br><br>
	  <b>Mail</b>
	  <input type='text' name='email'> - campo non obbligatorio - <br><br>
	  <input type="reset" name="Cancella tutti i campi">
	  <input type='submit' name='submit' value='Completa Registrazione'>
	  <b><a href="home.php"> Torna alla home</a></b>
	</fieldset>
      </form>
<?php
    if(isset($err)){
      echo"<b>$err</b>";
    };
?>

<?php
    
  }
  else{
    //la registrazione è stata effettuata con successo
    echo "Complimenti, registrazione effettuata con successo.";
    back("home.php");
  };
?>
      </td>
    </tr>
    <tr>
      <td>
	<img align="center" alt="Esempio sala del Ristorante" src="ristorante-1.jpg"/>
      </td>
    </tr>

<?php
    table_end();
  page_end();
?>
