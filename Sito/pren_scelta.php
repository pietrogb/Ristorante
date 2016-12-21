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
  
  if(isset($_POST['submit'])){
      $date=$_POST['sday'];
      $query="select p.Codice, u.Login, c.Nome, c.Cognome, u.Id, p.DataPren, p.DataCrea, p.NumeroPosti from Prenotazioni p join Clienti c on p.CodCliente=c.Id natural join Utenti u where p.DataPren='$date' and Codice Not in (select Codice from Ricevute) order by NumeroPosti";
      $query2="select pp.CodPrenotazione, pp.NPorzioni, Nome from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.CodPrenotazione natural join Piatti where p.DataPren='$date' and Codice Not in (select Codice from Ricevute) order by CodPrenotazione";
      $query3="select sum(NPorzioni), Nome from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.CodPrenotazione natural join Piatti where p.DataPren='$date' and Codice Not in (select Codice from Ricevute) group by CodPiatto order by CodPrenotazione;";
      $s1="la sera del $date";
      
      
  }
  elseif(isset($_POST['submit1'])){
      $date1=$_POST['sday1'];
      $date2=$_POST['sday2'];
      $query="select p.Codice, u.Login, c.Nome, c.Cognome, u.Id, p.DataPren, p.DataCrea, p.NumeroPosti from Prenotazioni p join Clienti c on p.CodCliente=c.Id natural join Utenti u where p.DataPren>='$date1' and p.DataPren<='$date2' and Codice Not in (select Codice from Ricevute) order by NumeroPosti";
      $query2="select pp.CodPrenotazione, pp.NPorzioni, Nome from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.CodPrenotazione natural join Piatti where p.DataPren>='$date1' and p.DataPren<='$date2' and Codice Not in (select Codice from Ricevute) order by CodPrenotazione";
      $query3="select sum(NPorzioni), Nome from Prenotazioni p join PrenotazioniPiatti pp on p.Codice=pp.CodPrenotazione natural join Piatti where p.DataPren>='$date1' and p.DataPren<='$date2' and Codice Not in (select Codice from Ricevute) group by CodPiatto order by CodPrenotazione;";
      $s1="le sere tra il $date1 ed il $date2";
      
      
    }
    elseif(!isset($_POST['submit']) || !isset($_POST['submit1']) ){
      form_start("POST","pren_scelta.php");
      echo "Seleziona un giorno specifico od un intervallo di cui vuoi conoscere le prenotazioni<BR>";
      echo "<p>Inserisci la data (nel formato MM/GG/AAAA): <input type='date' name='sday'  value='$date'>   ";
      echo "<input type='submit' name='submit' value='Procedi'>";
      echo "<input type='reset' value='Cancella'>";
      
      echo"<BR>";
      
      echo "<p>Inserisci la data (nel formato MM/GG/AAAA): <input type='date' name='sday1'  value='$date'> - <input type='date' name='sday2' value='$date'>   ";
      echo "<input type='submit' name='submit1' value='Procedi'>";
      echo "<input type='reset' value='Cancella'>";
    }
  
  if(isset($_POST['submit']) || isset($_POST['submit1']) ){
    
    echo"<p><a href='pren_scelta.php'><b><i>Nuova ricerca</i></b></a></p>";
    
    subtitle("Prenotazioni per $s1");
    //leggi i dati del cliente
    //cerca tutte le sue prenotazioni e stampale
    
    $ris=mysql_query($query,$conn);
    if(mysql_num_rows($ris) ==0)
	  echo"Non ci sono prenotazioni per $s1";
    else{
      $h=array("Codice prenotazione", "Username Cl.", "Nome Cl.","Cognome Cl.","Codice Cl.", "Data prenotazione", "data creazione", "numero posti");
      table_start($h);
      while($row=mysql_fetch_assoc($ris))
	echo_row($row);
      table_end();  
      }
      
    echo"<BR>";
    subtitle("Piatti da preparare per $s1");
    
    $ris2=mysql_query($query2,$conn);
    if(mysql_num_rows($ris2) ==0)
	  echo"Non ci sono prenotazioni per $s1";
    else{
      $h=array("Codice prenotazione", "N. Porzioni", "Nome piatto");
      table_start($h);
      while($row=mysql_fetch_assoc($ris2))
	echo_row($row);
      table_end();  
      }
    
    echo"<BR>";
    subtitle("Porzioni da preparare per $s1");
    
    $ris3=mysql_query($query3,$conn);
    if(mysql_num_rows($ris3) ==0)
	  echo"Non ci sono prenotazioni per $s1";
    else{
      $h=array("N. Porzioni", "Nome piatto");
      table_start($h);
      while($row=mysql_fetch_assoc($ris3))
	echo_row($row);
      table_end();  
      }
    
    }
  back("operazioni.php");
  page_end();
}
else header("Location: home.php")
?>