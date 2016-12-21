<?php
  require_once("libreria.php");
  /* verifica se sono stati immessi dei dati di login e se sono corretti */
  if(!isset($_POST['login']) || !isset($_POST['pwd'])){
    $err="Problemi di connessione";
  }
  else{
    $login=$_POST['login'];
    $pwd=SHA1($_POST['pwd']);
    
    $conn=dbConnect();
    
    if(isset($login) and $pwd == get_pwd($login)){
      $ris=mysql_query("select * from Clienti Natural Join Utenti u where u.Login='$login'",$conn);
      $rg=mysql_fetch_assoc($ris);
      $liv=$rg['livello'];
      $nome=$rg['Nome'];
      $id=$rg['Id'];
      session_start();
      $_SESSION['logged']=$login;
      $_SESSION['livello']=$liv;
      $_SESSION['nome']=$nome;
      $query="select max(CodLog) from Accessi;";
      $ris=mysql_query($query,$conn);
      $v=mysql_fetch_array($ris);
      $i=$v[0]+1;
      $ardate=mysql_fetch_array(mysql_query("select curdate()",$conn));
      $date=$ardate[0];
      $query="insert into Accessi values('$i','$id','$date')";
      mysql_query($query, $conn);
      header("Location: home.php");
    }
    else $err="Nome utente o password errata";

  };
  page_start("Ma certo! Ristorante");
  
?>
  <form method="post" syle="border=none" action="logcheck.php"> 
    <table border="0" bgcolor="#FFFF66" align="center">
    <tr><td width="1600" align="right">
      <fieldset syle="border=none">
      Nome: <input type="text" name="login">
      Password: <input type="password" name="pwd">
      <input type="submit" name="submit" vhtmlalue="Entra">
      <input type="reset" value="Cancella">
      <A HREF="registrazione.php"><b>      Registrati</b></A>
<?php
    if(isset($err))
      echo"<BR><b>Errore: $err</b>";
?> 
  </fieldset>
    </form>
  </td></tr></table>

<?php
  table_start_NH("");
    echo"<tr>";
      echo"<td width='250'>";
	Ul_start();
	  vis_menu();
	  Pt("orari.php","Guarda gli orari");
	Ul_end();
      echo<<<END
      </td>
      <td>
	<img align="right" width="500" height="500" alt="Esempio di piatto" src="plate.jpg" />
      </td>
    </tr>
END;
  table_end();
  page_end();
  ?>