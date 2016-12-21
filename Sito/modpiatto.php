<?php
  require_once("libreria.php");
  $login=authenticate() or header("Location: home.php");
  if (isset($_SESSION['logged']) && ($_SESSION['livello']==2)){
    $conn=dbConnect();
    inizio_pagina("Ma certo! Ristorante");
    subtitle("Modifica di un piatto del menu`");
    echo"<form method='POST' action='modpiatto.php' border='0'>";
    
    if(isset($_POST['submit1'])){
	$id=$_POST['Cod1'];
	$nome=$_POST['nome1'];
	$costo=$_POST['costo_i'] + ($_POST['costo_d'])/100;
	$costoi=(int)($costo);
	$costod=(int)(($costo-$costoi)*100);
	if(!isset($nome))
	  $err="Il nome non e` stato inserito o non e` composto di sole lettere";
	  else{
	    $query2="Update Piatti p set p.Nome='$nome', p.Costo=$costo where p.CodPiatto=$id;";
	    mysql_query($query2,$conn) or ($err="errore nell'inserimento tra i piatti");
	  };
      
    }
    elseif(isset($_POST['submit'])){
      if(isset($_POST['Cod'])){
	//prendi i dati dalla $_POST, mettili nelle celle, ed esegui 
	$id=$_POST['Cod'];
	$query="select* from Piatti where CodPiatto=$id;";
	$arr=mysql_query($query,$conn);
	$ris=mysql_fetch_array($arr);
	$nome=$ris[1];
	$costoi=(int)($ris[2]);
	$costod=(int)(($ris[2]-$costoi)*100);
      }
      else $err="non e` stato selezionato alcun piatto";
    };
      if(((isset($_POST['submit1'])) && isset($err)) || ((isset($_POST['submit'])) && (isset($_POST['Cod'])))){
	echo<<<END
	  Nome: <input type="text" name="nome1" size="70" value='$nome' >
	  <BR>
	  Costo: <input type="number" name="costo_i" min=0 size='3' value='$costoi'>,<input type="number" name="costo_d" min=0 max=99 size='2' value='$costod'><BR>
	  <input type="hidden" name="Cod1" value='$id'>
	  <input type="submit" name="submit1" value="Procedi">
	
END;
    
      
    };
    if(isset($_POST['submit1'])){
      if(!isset($err))
	echo"<BR><b>Il piatto $nome e` stato modificato con successo<BR></b>";
      else{ echo"<BR><b>E` stato riscontrato un errore: </b>";
	echo"$err<BR>";
      };
    }
    elseif(isset($err))
      echo"$err<BR>";
    ;
      
     
      
    $h=array("Antipasti","Primi","Secondi","Contorni","Dolci","PiattiSpeciali","Bevande");
    $s=array("Antipasti","Primi","Secondi","Contorni","Dolci","Piatti Speciali","Bevande");
    
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
	  echo"<td><input type='radio' name='Cod' value=$row[0]></td>";
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
  else header("Location: home.php")
  ?>