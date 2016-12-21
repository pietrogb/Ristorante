<?php
  require("libreria.php");
  
$login=authenticate() or header("Location: home.php");
if (isset($_SESSION['logged']))
{
  inizio_pagina("Ma certo! Ristorante");
  subtitle("Visualizza le tue Prenotazioni");
  $conn=dbConnect();
  //leggi i dati del cliente
  //cerca tutte le sue prenotazioni e stampale
  $aid=mysql_query("select Id from Utenti where Login='$login'",$conn);
  $did=mysql_fetch_array($aid);
  $idcliente=$did[0];
  $aid=mysql_query("select curdate()",$conn);
  $dta=mysql_fetch_array($aid);
  $date=$dta[0];
  subtitle("Prenotazioni attive");
  $query_pren="select Codice, DataPren, DataCrea, NumeroPosti from Prenotazioni where CodCliente='$idcliente' and DataPren>='$date' and Codice Not in (select Codice from Ricevute) order by DataPren desc";
  $ris=mysql_query($query_pren,$conn);
  if(mysql_num_rows($ris) ==0)
    echo"Non ci sono prenotazioni pendenti";
  else{
    $h=array("Codice prenotazione", "Data prenotazione", "Data creazione", "Numero posti");
    table_start($h);
    while($row=mysql_fetch_assoc($ris))
      echo_row($row);
    table_end();  
    }
  
  subtitle("Prenotazioni scadute");
  $query_pren="select Codice, DataPren, DataCrea, NumeroPosti from Prenotazioni where CodCliente='$idcliente' and DataPren<'$date' order by DataPren desc";
  $ris=mysql_query($query_pren,$conn);
  if(mysql_num_rows($ris)==0)
    echo"Non ci sono prenotazioni scadute";
  else{
    $h=array("Codice prenotazione", "Data prenotazione", "Data creazione", "Numero posti");
    table_start($h);
    while($row=mysql_fetch_assoc($ris))
      echo_row($row);
    table_end();  
    }
    
  subtitle("Ricevute emesse");
  $query_pren="select Codice, DataPren, DataCrea, NumeroPosti, PrezzoPrenotazione(Codice), Totale, TotPunti from Prenotazioni natural join Ricevute where CodCliente='$idcliente' order by DataPren desc";
  $ris=mysql_query($query_pren,$conn);
  if(mysql_num_rows($ris)==0)
    echo"Non sono stati fatti acquisti";
  else{
    $h=array("Codice prenotazione", "Data prenotazione", "Data creazione", "Numero posti","Totale","TotalePagato","Punti Spesi");
    table_start($h);
    while($row=mysql_fetch_assoc($ris))
      echo_row($row);
    table_end();  
    }
      
  back("operazioni.php");
  page_end();
  }
  else header("Location: home.php")
?>