<?php
  require("libreria.php");
  
$login=authenticate() or header("Location: home.php");
if (isset($_SESSION['logged']) && ($_SESSION['livello']==2))
{
  inizio_pagina("Ma certo! Ristorante");
  $conn=dbConnect();
  $aid=mysql_query("select curdate()",$conn);
  $dta=mysql_fetch_array($aid);
  $date=$dta[0];
  subtitle("Prenotazioni per la sera del $date");
  //leggi i dati del cliente
  //cerca tutte le sue prenotazioni e stampale
  
  $query="select p.Codice, u.Login, c.Nome, c.Cognome, u.Id, p.DataPren, p.DataCrea, p.NumeroPosti from Prenotazioni p join Clienti c on p.CodCliente=c.Id natural join Utenti u where p.DataPren='$date' and Codice Not in (select Codice from Ricevute) order by NumeroPosti";
  $ris=mysql_query($query,$conn);
  if(mysql_num_rows($ris) ==0)
    echo"Non ci sono prenotazioni per la sera corrente";
  else{
    $h=array("Codice prenotazione", "Username Cl.", "Nome Cl.","Cognome Cl.","Codice Cl.", "Data prenotazione", "data creazione", "numero posti");
    table_start($h);
    while($row=mysql_fetch_assoc($ris))
      echo_row($row);
    table_end();  
    }
    
  echo"<BR>";
  subtitle("Piatti da preparare per la serata corrente");
  
  $query="select pp.CodPrenotazione, pp.NPorzioni, Nome from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.CodPrenotazione natural join Piatti where p.DataPren='$date' and Codice Not in (select Codice from Ricevute) order by CodPrenotazione";
  $ris2=mysql_query($query,$conn);
  if(mysql_num_rows($ris2) ==0)
    echo"Non ci sono prenotazioni per la sera corrente";
  else{
    $h=array("Codice prenotazione", "N. Porzioni", "Nome piatto");
    table_start($h);
    while($row=mysql_fetch_assoc($ris2))
      echo_row($row);
    table_end();  
    }
  
  echo"<BR>";
  subtitle("Porzioni da preparare per la serata corrente");
  
  $query="select sum(NPorzioni), Nome from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.CodPrenotazione natural join Piatti where p.DataPren='$date' and Codice Not in (select Codice from Ricevute) group by CodPiatto order by CodPrenotazione;";
  $ris3=mysql_query($query,$conn);
  if(mysql_num_rows($ris3) ==0)
    echo"Non ci sono prenotazioni per la sera corrente";
  else{
    $h=array("N. Porzioni", "Nome piatto");
    table_start($h);
    while($row=mysql_fetch_assoc($ris3))
      echo_row($row);
    table_end();  
    }
  
      
  back("operazioni.php");
  page_end();
}
else header("Location: home.php")
?>