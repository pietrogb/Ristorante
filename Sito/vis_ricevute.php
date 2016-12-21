<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $conn=dbConnect();
    inizio_pagina("Ma certo! Ristorante");
    subtitle("Elenco ricevute emesse");
    $query= "select Codice, DataPren, CodCliente, Totale from Ricevute natural join Prenotazioni order by DataPren";
    $ris=mysql_query($query,$conn);
    $n=mysql_num_rows($ris);
    if($n>0){
      $h=array('Codice','Data emissione','Cod. Cliente','Totale (Euro)');
      table_start($h);
      while($row= mysql_fetch_assoc($ris))
	echo_row($row);
      table_end();
    }
    else{
      echo"Non sono ancora state emesse ricevute";
    };
    back("operazioni.php");
  page_end();
}
else header("Location: home.php");
?>