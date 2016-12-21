<?php
  require("libreria.php");
  
  /* se l'utente e` gia` autenticato, va direttamente alla pagina delle operazioni.php*/
$login=authenticate();

if (isset($_SESSION['logged']))
  header("Location: operazioni.php");

else{
  page_start("Ma certo! Ristorante");
  };
  
  echo<<<END
  <form method="post" syle="border=none" action="logcheck.php"> 
    <table border="0" bgcolor="#FFFF66" align="center">
    <tr><td width="1600" align="right">
      <fieldset syle="border=none">
      Nome: <input type="text" name="login">
      Password: <input type="password" name="pwd">
      <input type="submit" name="submit" vhtmlalue="Entra">
      <input type="reset" value="Cancella">
      <A HREF="registrazione.php"><b>      Registrati</b></A>
      </fieldset>
    </form>
  </td></tr></table>
END;

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
