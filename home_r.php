<html>
<?php
  require("f1.php");
  page_start("Ristorante");
?>	
	
  <body bgcolor="#FF4000">
    <table border="0" align="right">
    <tr><td>
	<form action="pagina_clienti.php" method="post">
	<fieldset>
	<h4><p align="center">Login</p></h4>	
	Username
	<input type="text" name="username">
	<BR>Password
	<input type="password" name="password" maxlength="8">
	<p align="center"><input type="submit" name="submit" value="Conferma"></p>
	</td></tr></table>


	<table border="1">
    <tr>
      <td>registrazione nuovo utente</td>
    </tr>
    <tr>
      <td>lista pagine</td>
      <td>
	<img align="right" width="500" height="500" alt="Esempio di piatto" src="plate.jpg" />
      </td>
    </tr>
    </table>
  </body>
</html>
