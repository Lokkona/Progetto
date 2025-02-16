
<!DOCTYPE html>
<html>
<head>
  <title> Tabella con PHP </title>
</head>
<body>

  <form method="post">
    <div> <label for="righe">Numero righe</label>
        <input type="number" name="righe" value="1"></div>
    <div> <label for="righe">Numero colonne</label>
        <input type="number" name="colonne" value="1"></div>
        <input type="submit" name="inviato" value="Invia">
      </form>

<?php if(isset($_POST["inviato"])) { ?>

<table style="border: solid 1px;">
<?php
for($i=0;$i<=$_POST["righe"];$i++)
{
  echo "<tr>";

for ($j=1;$j<=$_POST["colonne"];$j++)
  {
    if($i == 0)
    {
      $carattere = chr(65+$j-1);
      echo "<th> Colonna $carattere </th>";
    }
    else {
      echo "<td>$i * $j = ".$i*$j."</td>";
    }
  }
  echo "</tr>";
  }
  ?>
</table>
 <?php } 
?>

</body>
</html>