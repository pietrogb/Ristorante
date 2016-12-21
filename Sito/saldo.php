<?php
  require("libreria.php");
$login=authenticate() or header("Location: home.php");

if (isset($_SESSION['logged']))
{
  inizio_pagina("Ma certo! Ristorante");
    $conn=dbConnect();
    $query="select cf.TotPunti from CartaFedelta cf, Utenti u where u.Login='$login' and u.Id=cf.IdCliente";
    $r=mysql_query($query, $conn);
    $p=mysql_fetch_array($r);
    $tot=$p[0];
    echo"Il tuo saldo punti ammonta a: <b><u> $tot punti</u></b><BR>Un punto permette uno sconto di __€<BR>";
    echo"<BD>";
    echo"<img align='center' alt='Immagine per la pagina del saldo punti' src='punti.jpg' />";
    back("operazioni.php");
  page_end();
}
else
  header("Location: home.php");
?>