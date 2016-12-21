<?php
  include("libreria.php");
  $login=authenticate() or header("Location: home.php");
  $title='Ma certo! Ristorante';
  inizio_pagina($title);
  echo<<<END
  <div id="menu" style="position: absolute; float:left; padding: 20px;">
END;
  Ul_start();
    echo"<li><b>Home</b></li>";
    if($_SESSION['livello']==2)
    {
      echo"<li>Operazioni gestore</li>";
      Ul_start();
	echo"<li>Visione prenotazioni</li>";
	Ul_start();
	  Pt("pren_serata.php", "Sera Corrente");
	  Pt("pren_scelta.php", "Scelta data");
	Ul_end();
	echo"<li>Gestione menù</li>";
	Ul_start();
	  Pt("inspiatto.php", "Aggiunta piatti");
	  Pt("modpiatto.php","Modifica menù");
	  Pt("elimpiatto.php","Eliminazione piatti");
	Ul_end();
	echo"<li>Gestione Clienti</li>";
	Ul_start();
	  Pt("elimclienti.php", "Eliminazione clienti");
	  Pt("vislog.php", "Visualizza log");
	  Pt("vispunti.php", "Visualizza Punti clienti");
	Ul_end();
	echo"<li>Gestione Personale</li>";
	Ul_start();
	  Pt("vis_pers.php", "Visualizza personale");
	  Pt("aggpers.php", "Aggiungi personale");
	  Pt("elimpers.php", "Rimuovi personale");
	Ul_end();
      echo"<BR>";
      Pt("ricevute.php", "Emissione ricevute");
      Pt("vis_ricevute.php", "Visione ricevute");
      echo"<BR>";
      Pt("pren_libera.php", "Prenotazione libera");
      Ul_end();
    }
  echo"<BR>";
  echo"<li>Gestione Profilo</li>";
    Ul_start();
      Pt("vis_pren.php", "Visualizza le tue prenotazioni");
      Pt("crea_pren.php", "Crea una nuova prenotazione");
      Pt("saldo.php","Visione Punti");
      Pt("aggiornadati.php","Cambio dati personali");
      Pt("cambiapassword.php","Cambio Password");
    Ul_end();
  echo"<BR>";
  vis_menu();
  Pt("orari.php","Guarda gli orari");
  Ul_end();
echo<<<END
  </div>
  
  <div id="content" style="position: absolute;left: 50%; padding: 20px;">
    <img width="368" height="569" alt="Esempio di piatto" src="img2.jpg" />
  </div>
END;
  page_end();
?>