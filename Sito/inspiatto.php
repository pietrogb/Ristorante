<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $conn=dbConnect();
    inizio_pagina("Ma certo! Ristorante");
    subtitle("Inserimento d'un nuovo piatto nel menu`");
    if(isset($_POST['submit'])){
      $nome=$_POST['nome'];
      $costo=$_POST['costo_i'] + $_POST['costo_d']/100;
      $port=$_POST['portata'];
      if(!isset($nome))
	$err="Il nome non e` stato inserito o non e` composto di sole lettere";
      elseif(!isset($_POST['portata']))
	$err="Non e` stata selezionata la portata";
	else{
	  $query1="select max(CodPiatto) from Piatti";
	  $ris=mysql_query($query1,$conn);
	  if(!isset($ris))
	    $n=1;
	  else{
	    $temp=mysql_fetch_array($ris);
	    $n=1+$temp[0];
	  };
	  $query2="insert into Piatti values($n,'$nome',$costo)";
	  mysql_query($query2,$conn) or ($err="errore nell'inserimento tra i piatti");
	  $h=array("Antipasti","Primi","Secondi","Contorni","Dolci","PiattiSpeciali","Bevande");
	  $j=$h[$port];
	  $query3="insert into $j values($n)";
	  mysql_query($query3,$conn) or ($err="errore nell'inserimento tra le portate");
	};
      if(!isset($err))
	echo"<b>Il piatto $nome e` stato inserito con successo in: $j<BR></b";
      else{ echo"<b>Non e` stato possibile inserire il piatto con successo perche`:</b><BR>";
	echo"$err";
	
      }
    };
      form_start("POST","inspiatto.php");
      ?>
	Nome: <input type="text" name="nome" size="50">
	<BR>
	Costo: <input type="number" name="costo_i" min=0 size='3'>,<input type="number" name="costo_d" min=0 max=99 size='2'><BR>
	Tipo:<BR>
	  <input type="radio" name="portata" value="0"> Antipasto<BR>
	  <input type="radio" name="portata" value="1"> Primo<BR>
	  <input type="radio" name="portata" value="2"> Secondo<BR>
	  <input type="radio" name="portata" value="3"> Contorno<BR>
	  <input type="radio" name="portata" value="4"> Dolce<BR>
	  <input type="radio" name="portata" value="5"> Piatto Speciale<BR>
	  <input type="radio" name="portata" value="6"> Bibita<BR>
	  
      <BR>
      <?php
      form_end();
    back("operazioni.php");
    page_end();
}
else
  header("home.php");
  ?>