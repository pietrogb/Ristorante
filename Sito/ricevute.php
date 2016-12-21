<?php
  include("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $title='Ma certo! Ristorante';
    inizio_pagina($title);
    subtitle("Emissione ricevute");
    $conn=dbConnect();
    if(isset($_POST['submit1']))
    {
      $codPren=$_POST['CodPren'];
      $npunti=$_POST['npunti'];
      $query="call InserisciRicevuta($codPren,$npunti)";
      $ris=mysql_query($query,$conn);
      $query2="select Codice, Totale, FLOOR(PrezzoPrenotazione($codPren) /10) from Ricevute where Codice=$codPren";
      $ris2=mysql_query($query2,$conn);
      $h=array("Codice Prenotazione", "Totale Pagato", "N. punti aggiunti");
      table_start($h);
	while($row=mysql_fetch_assoc($ris2))
	  echo_row($row);
      table_end();
      echo"<p><a href='ricevute.php'><b><i>Emetti una nuova ricevuta </i></b></a></p>";
    }
    else{
      if(isset($_POST['submit']))
	{
	$num=$_POST['B1'];
	subtitle("Estremi prenotazione");
	$query1="select p.Codice, p.DataCrea, p.DataPren, p.NumeroPosti, u.Login, PrezzoPrenotazione($num) from Prenotazioni p join Utenti u on p.CodCliente=u.Id where p.Codice=$num";
	$ris1=mysql_query($query1,$conn);
	$h=array("Codice pren", "Data pren", "Data creaz", "N. posti","Login Cl.","Tot (Euro)");
	table_start($h);
	while($row1=mysql_fetch_assoc($ris1))
	  echo_row($row1);
	table_end();
	form_start("POST","ricevute.php");
	$aid=mysql_fetch_array(mysql_query("select MaxPunti($num)",$conn));
	$mpunti=$aid[0];
	  echo"Inserisci i punti da togliere<input type='number' name='npunti' min=0 max=$mpunti size='3' value='0'> /$mpunti";
	  echo"<input type='hidden' name='CodPren' value=$num>";

	echo"<input type='submit' name='submit1' value='Procedi'>";
	echo"<input type='reset' value='Cancella'>";
	echo"</fieldset>";
	echo"</form>";
	
	echo"<BR>";
	
	subtitle("Riepilogo prenotazione");
	$query2="select pp.Nporzioni, pi.Nome, pi.Costo from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.COdPrenotazione natural join Piatti pi where p.Codice=$num";
	$ris2=mysql_query($query2,$conn);
	$h=array("N. Porzioni","Nome Piatto","Costo(Euro)");
	table_start($h);
	  while($row=mysql_fetch_assoc($ris2))
	    echo_row($row);
	table_end();
	}
      
      else
	{
	$aid=mysql_query("select curdate()",$conn);
	$did=mysql_fetch_array($aid);
	$aid=mysql_query("select curdate()",$conn);
	$dta=mysql_fetch_array($aid);
	$date=$dta[0];
	
	subtitle("Prenotazioni attive");
	$query_pren="select Codice, DataPren, DataCrea, NumeroPosti, PrezzoPrenotazione(Codice) from Prenotazioni where DataPren='$date' and Codice Not in (select Codice from Ricevute) order by DataPren desc";
	$ris=mysql_query($query_pren,$conn);
	if(mysql_num_rows($ris) ==0)
	  echo"Non ci sono prenotazioni pendenti";
	else{
	  $h=array("Codice pren", "Data pren", "Data creaz", "N. posti","Tot (Euro)","Seleziona");
	  form_start('POST','ricevute.php');
	    table_start($h);
	    while($row=mysql_fetch_assoc($ris))
	      echo_row_RDB($row);
	    table_end();
	  form_end();
	  }
	
	subtitle("Prenotazioni scadute");
	$query_pren="select Codice, DataPren, DataCrea, NumeroPosti, PrezzoPrenotazione(Codice) from Prenotazioni where DataPren<'$date' order by DataPren desc";
	$ris=mysql_query($query_pren,$conn);
	if(mysql_num_rows($ris)==0)
	  echo"Non ci sono prenotazioni scadute";
	else{
	  $h=array("Codice prenotazione", "Data prenotazione", "Data creazione", "Numero posti","Totale (Euro");
	  table_start($h);
	  while($row=mysql_fetch_assoc($ris))
	    echo_row($row);
	  table_end();  
	}
      }
    }
    back("operazioni.php");
    page_end();
  }
  else
    header("Location: home.php");
?>