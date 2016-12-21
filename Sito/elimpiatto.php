<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $conn=dbConnect();
    inizio_pagina("Ma certo! Ristorante");
    subtitle("Eliminazione piatti dal menu`");
    if(isset($_POST['submit'])){
      if(isset($_POST['Cod'])){
	$cods=$_POST['Cod'];
	
	foreach($cods as $d){
	  $query_el="delete from Piatti where CodPiatto='$d'";
	  mysql_query($query_el,$conn) or err('impossibile eliminare Id=$d');
	  echo"Il piatto con codice='$d' e` stato cancellato con successo<BR>";
	}
      }
      else subtitle("nessun piatto selezionato");
    }
      
    
    $h=array("Antipasti","Primi","Secondi","Contorni","Dolci","PiattiSpeciali","Bevande");
    $s=array("Antipasti","Primi","Secondi","Contorni","Dolci","Piatti Speciali","Bevande");
    
    
    form_start("POST","elimpiatto.php");
    table_start_NH();
    for ($i=0; $i<=6; $i++) {;
      $name=$s[$i];
      $table=$h[$i];
      echo "<tr><th>$name </th></tr>";
      $q="select p.CodPiatto, p.Nome, p.Costo from Piatti p Join $table t where p.CodPiatto=t.Codice";
      $ris=mysql_query($q,$conn);
      $num_righe=mysql_num_rows($ris);
      if(!$num_righe)
	echo"<tr><td>Non ci sono $name</td></tr>";
      else{
	while($row=mysql_fetch_array($ris)){
	  echo"<tr><td>".$row["Nome"]."</td><td>".number_format($row["Costo"],2)."€</td>";
	  echo"<td><input type='checkbox' name='Cod[]' value=$row[0]></td>";
	  echo"</td></tr>";
	}
      }
    }
    table_end();
    form_end();
    back("operazioni.php");
    page_end();
  }
  else header("Location: home.php")
  ?>