<?php
  require("libreria.php");
  
$login=authenticate() or header("Location: home.php");
if (isset($_SESSION['logged']) && ($_SESSION['livello']==2))
{
  inizio_pagina("Ma certo! Ristorante");
  subtitle("Inserisci una nuova prenotazione libera");
  $conn=dbConnect();
  
  if(isset($_POST['submit'])){
    $n=0;
    $pl=$_POST['nporz'];
    foreach($pl as $d)
      $n+=$d;
    if($n!=0){
      //leggi i dati del cliente
      //crea la prenotazione in Prenotazioni
      //poi inserisci ogni piatto in PrenotazioniPiatti
      $dataP=$_POST['sday'];
      $np=$_POST['npers'];
      $idcliente=$_POST['cliente'];
      $idpr=creapren($idcliente,$dataP,$np) or $err="impossibile inserire la prenotazione corrente";
      if(!isset($err)){
	$cods=$_POST['ids'];
	$i=0;
	foreach($pl as $num){
	  if($num>0){
	    $codpiatto=$cods[$i];
	    $query="insert into PrenotazioniPiatti values($idpr,$codpiatto,$num)";
	    mysql_query($query,$conn) or $err="errore d'un piatto all'interno della prenotazione";
	    if(isset($err)) echo"$err";
	  }
	  $i++;
	}
	//mysql_query($query_el,$conn) or $err="impossibile inserire la prenotazione corrente; reinserire i dati";
	echo"La prenotazione e` stata inserita con successo<BR>";
      }
    }
    else subtitle("nessun piatto selezionato");
  };
  
  $h=array("Antipasti","Primi","Secondi","Contorni","Dolci","PiattiSpeciali","Bevande");
  $s=array("Antipasti","Primi","Secondi","Contorni","Dolci","Piatti Speciali","Bevande");
  echo"<form method='POST' action='pren_libera.php' border='0'>";
  echo"Inserisci il cliente";
  echo"<select name='cliente'>";
  $query="select Id, Login, Nome, Cognome from Clienti natural join Utenti";
  $ris=(mysql_query($query,$conn));
  while($row=mysql_fetch_array($ris)){
    //echo"$row[0] $row[1] $row[2]";
    echo"<option value='$row[Id]'>$row[Id] - $row[Login] - $row[Nome] $row[Cognome] </option>";
  }
  echo"</select>";
  echo"<BR>Inserisci il numero di persone:<input type='number' name='npers' min=1 size='3' value='1'>";
  $ardate=mysql_fetch_array(mysql_query("select curdate()",$conn));
  $date=$ardate[0];
  echo"     Inserisci la data (nel formato MM/GG/AAAA): <input type='date' name='sday' min='$date' value='$date'><BR>";
  
  table_start_NH(); 
  for ($i=0; $i<=6; $i++) {
    $name=$s[$i];
    $table=$h[$i];
    echo "<tr><th colspan='3'>$name </th></tr>";
    $q="select p.CodPiatto, p.Nome, p.Costo from Piatti p Join $table t where p.CodPiatto=t.Codice";
    $ris=mysql_query($q,$conn);
    $num_righe=mysql_num_rows($ris);
    if(!$num_righe)
      echo"<tr><td>Non ci sono $name</td></tr>";
    else{
      while($row=mysql_fetch_array($ris)){
	echo"<tr><td>".$row["Nome"]."</td><td>".number_format($row["Costo"],2)."€</td>";
	echo"<input type='hidden' name='ids[]' value='$row[CodPiatto]'>";
	echo"<td><input type='number' name='nporz[]' min=0 size='3' value='0'></td>";
	echo"</td></tr>";
      }
    }
  }
    
  table_end();
  if(!isset($_POST['submit']) || !isset($_POST['Cod'])){
    echo"<input type='submit' name='submit' value='Procedi'>";
    echo"<input type='reset' value='Cancella'>";
  }
  echo"</form>";
  back("operazioni.php");
  page_end();
}
else header("Location: home_r.php")
?>