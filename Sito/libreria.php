<?php
  /***************************************************/
  /*FUNZIONI GENERALI PER LA CREAZIONE DI PAGINE HTML*/
  /***************************************************/
  
  //funzione che crea l'inizio di una pagina web, con $title come titolo
    function page_start($title){
    echo<<<END
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="it" lang="it">
      <html><head><title>$title</title></head><body bgcolor="#F0E0B2">
      <h1 align='center'><i>$title</i></h1>
END;
    };
  
  //funzione che crea l'inizio di una pagina web, con $title come titolo 
  function inizio_pagina($title){
    echo<<<END
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="it" lang="it">
    <head><title>"Ma certo! Ristorante"</title></head><body bgcolor="#F0E0B2">
    <h1 align='center'><i>$title</i></h1>
END;
    echo"<div id='header'>";
    echo"<table border='0' bgcolor='#FFFF66' cellpadding='10' align='center' height='42'><frameset>";
    echo"<tr><td width='1600' align='right' >Benvenuto <b> ".$_SESSION['nome']." </b>";
    Lk("logout.php","Logout");
    echo"</td></tr></frameset></table>";
    echo"</div>";
    };
  
    function subtitle($str){
      echo"<h3>$str</h3>";
    };
    
    function back($url){
      echo"<p><a href=$url>Torna alla pagina <b><i>$url</i></b></a></p>";
    };
   //funzione che crea il link alla pagina $pg, passando col metodo get nel parametro $bg l'informazione contenuta nel campo $info 
    function Lk($pg,$info){
      echo"<td><a href=".$pg."?B1=".$info."><b><i>$info</i></b></a></td>";
    };
   //funzione che genera un punto d'un elenco puntato come link alla pagina $l visualizzando il testo in $t
    function Pt($l, $t){
      echo"<li><a href=".$l.">$t</a></li>";
    };
    
   //funzione che genera un punto d'un elenco puntato come link alla pagina $l visualizzando il testo in $t
    function pt_i($l, $info, $t){
      echo"<li><a href=".$l."?B1=".$info.">$t</a></li>";
    };
    
   //funzione genera l'apertura d'un nuovo elenco puntato
    function Ul_start(){
      echo"<ul>";
    };
   //funzione genera la chiusura di un elenco puntato
    function Ul_end(){
      echo"</ul>";
    };
   //funzione che conclude una pagina html 
    function page_end(){
      echo"</body></html>";
    };
    
   //funzione che genera il punto di una lista 
    function Point($info){
      echo"<li>$info</li>";
    };
    
    function back_home(){back("home.php");};
    
    /**************************************/
    /*FUNZIONI PER LA CREAZIONE DI TABELLE*/
    /**************************************/
    
   //funzione che genera l'inizio d'una tabella avente come header il contenuto di $head
    function table_start($head){
      echo"<table align='center' border='1'><tr>";
      foreach($head as $value){
	echo"<th>$value</th>";
      }
      echo"</tr>";
    };
    
    function table_start_NH(){
      echo"<table align='center' border='1'>";
    };
    
   //funzione che stampa la riga $row
    function echo_row($row) {
      echo"<tr>";
      foreach ($row as $field)
	echo"<td>$field</td>";
      echo"</tr>";
    };
    
	function echo_row_RDB($row) {
      echo"<tr>";
      foreach ($row as $field)
	echo"<td>$field</td>";
      echo"<td align='center'><input type='radio' name='B1' value=$row[Codice]></td>";
      echo"</tr>";
    };
    
    function echo_row_CK($row) {
      echo"<tr>";
      foreach ($row as $field)
		echo"<td>$field</td>";
      echo"<td><input type='checkbox' name='Ids[]' value=$row[Id]></td>";
      echo"</tr>";
    };

    function table_end(){
      echo"</table>";
    };
    
    
    /*funzione per la genrazione di form*/
    function form_start($type, $dest){
      echo"<form method='$type' action='$dest' border='0'>";
      echo"<fieldset style='border:none'>";
    };
    
   //funzione per la chiusura d'una form 
    function form_end(){
      echo"<input type='submit' name='submit' value='Procedi'>";
      echo"<input type='reset' value='Cancella'>";
      echo"</fieldset>";
      echo"</form>";
    };
  
  /*****************************************/
  /*FUNZIONE PER LA CONNESSIONE AL DATABASE*/
  /*****************************************/
  
  function dbConnect() {
    $server="basidati";
    $username="pgabelli";
    $password="L4t7t9LX";
    $dbname="pgabelli-PR";
    $conn=mysql_connect($server,$username,$password)
      or die("Impossibile effettuare la connessione");
    mysql_select_db($dbname,$conn);
    return $conn;
  }


  /*********************************/
  /* FUNZIONI PER L'AUTENTICAZIONE */
  /*********************************/

  function get_pwd($login) {
    $conn = dbConnect();
    $query= "SELECT * FROM Utenti WHERE Login='$login'";
    $result=mysql_query($query,$conn)
      or die("Query fallita" . mysql_error($conn));
    $output=mysql_fetch_assoc($result);
    if ($output)
      return $output['password'];
    else 
      return FALSE;
  }

  /*inizia la sessione e verifica che l'utente sia autenticato */
  function authenticate() {
    session_start();
    if(isset($_SESSION['logged'])){
      $login=$_SESSION['logged'];
      if (isset($login)) {
      return $login;
      }
    }
    return FALSE;  
  }
  
  /********************************/
  /*FUNZIONI PER L'ACCESSO AL MENÙ*/
  /********************************/
  
  function vis_menu(){
    pt_i("risultato.php","0","Guarda tutto il menu`");
    Ul_start();
      pt_i("risultato.php","1","Antipasti");
      pt_i("risultato.php","2","Primi");
      pt_i("risultato.php","3","Secondi");
      pt_i("risultato.php","4","Contorni");
      pt_i("risultato.php","5","Dolci");
      pt_i("risultato.php","6","Piatti speciali");
      pt_i("risultato.php","7","Bibite");
    Ul_end();
  };
  
  function result($conn,$i){
    $h=array("Menu","Antipasti","Primi","Secondi","Contorni","Dolci","PiattiSpeciali","Bevande");
    $s=array("Menu","Antipasti","Primi","Secondi","Contorni","Dolci","Piatti Speciali","Bevande");
    
    $j=$s[$i];
    echo"<h3><h3 align='center'>$j</h3>";
    $q=$h[$i];
    $result=mysql_query("Select p.Nome, p.Costo from Piatti p join $q q on p.CodPiatto=q.Codice",$conn)
    or die("Query fallita");
    $head=array("Nome","Costo");
    $num_righe=mysql_num_rows($result);
    if(!$num_righe)
      echo"<p align='center'>Non ci sono ".$s[$i]."</p>";
    else{
      table_start_NH($head);
      while($row=mysql_fetch_assoc($result))
	echo"<tr><td>".$row["Nome"]."</td><td>".number_format($row["Costo"],2)."€</td></tr>";
      table_end();
    }
    echo"<BR";
  };
  
  function menu($conn){
    result($conn,1);
    result($conn,2);
    result($conn,3);
    result($conn,4);
    result($conn,5);
    result($conn,6);
    result($conn,7);
  };

  /*********************/
  /*FUNZIONI DI UTILITÀ*/
  /*********************/
  
  function chkEmail($email){
    $email = trim($email);
    // se la stringa è vuota sicuramente non è una mail
    if(!$email) {
      return false;
    }
    // controllo che ci sia una sola @ nella stringa
    $num_at = count(explode( '@', $email )) - 1;
    if($num_at != 1) {
      return false;
    }
    // controllo la presenza di ulteriori caratteri "pericolosi":
    if(strpos($email,';') || strpos($email,',') || strpos($email,' ')) {
      return false;
    }
    // la stringa rispetta il formato classico di una mail?
    if(!preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email)) {
      return false;
    }
    return true;
  };

  function new_Client($nome, $cognome,$mail,$telefono) {
      /* si connette e seleziona il database da usare */
      $conn = dbConnect();
      $row=mysql_fetch_array(mysql_query("select count(*) from Clienti", $conn));
      if( $row[0] == 0)
	$n_ut=0;
      else {
	$row=mysql_fetch_array(mysql_query("select max(u.Id) from Clienti u", $conn));
	$n_ut=$row[0];
      }
      $n_ut++;
      $ardate=mysql_fetch_array(mysql_query("select curdate()",$conn));
      $date=$ardate[0];
      $query="INSERT INTO Clienti VALUES ('$n_ut', '$nome', '$cognome','$mail','$telefono','$date')";
      mysql_query($query,$conn);
      return $n_ut;
    }

  function new_user($n_ut,$login, $password) {
    /* si connette e seleziona il database da usare */
    $conn = dbConnect();
    /* preparazione dello statement */
    $s=SHA1($password);
    $query="INSERT INTO Utenti VALUES ('$n_ut', '$login', '$s','1')";
    $result=mysql_query($query,$conn);
    return $result;
  }
  
  function creapren($Id, $dataP, $num){
    $conn=dbConnect();
    $ardate=mysql_fetch_array(mysql_query("select curdate()",$conn));
    $dt=$ardate[0];
    $arpren=mysql_fetch_array(mysql_query("select max(Codice) from Prenotazioni",$conn));
    $np=1+$arpren[0];
     $query="insert into Prenotazioni values('$np', '$dt', '$dataP', '$num', '$Id')";
     mysql_query($query,$conn) or $err="errore nell'inserimento della prenotazione";
    return $np;
  };
?>