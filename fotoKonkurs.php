<?php if (isset($_GET['code'])) {die(highlight_file(__FILE__, 1));}?>
<?php
require ('conf.php');

global $yhendus;

//kuvamine
if (isset($_REQUEST["kuva_id"])) {
    $paring = $yhendus->prepare("UPDATE fotokonkurss SET avalik = 1 WHERE id = ?");
    $paring->bind_param("i", $_REQUEST['kuva_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

//peitmine
if (isset($_REQUEST["peida_id"])) {
    $paring = $yhendus->prepare("UPDATE fotokonkurss SET avalik = 0 WHERE id = ?");
    $paring->bind_param("i", $_REQUEST['peida_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

//delete
if(isSet($_REQUEST["kustuta"])) {
    $paring = $yhendus->prepare("DELETE FROM fotokonkurss WHERE id = ?");
    $paring->bind_param("i", $_REQUEST['kustuta']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}
// delete komment
if(isSet($_REQUEST["kustuta_komment"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET kommentaarid=' ' WHERE id=?");
    $paring->bind_param("i", $_REQUEST['kustuta_komment']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}
//update punktid
if(isSet($_REQUEST["0punkt"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET punktid=0 WHERE id=?");
    $paring->bind_param("i", $_REQUEST['0punkt']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}
//lisamine andmetabelisse
/*if(isSet($_REQUEST["nimetus"])){
    $paring=$yhendus->prepare("INSERT INTO fotokonkurss(fotoNimetus, autor, pilt, lisamisAeg) VALUES (?, ?, ?, NOW());");
    $paring->bind_param("sss", $_REQUEST['nimetus'], $_REQUEST['autor'], $_REQUEST['pilt']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}*/
//update +1punkt
/*if(isSet($_REQUEST["lisa1punkt"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET punktid=punktid+1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST['lisa1punkt']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}
//update -1punkt
if(isSet($_REQUEST["kustuta1punkt"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET punktid=punktid-1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST['kustuta1punkt']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}
//update - lisa kommentaar
if(isSet($_REQUEST["uus_komment"]) && !empty($_REQUEST["komment"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET kommentaarid=Concat(kommentaarid, ?) WHERE id=?");
    $komment2=$_REQUEST['komment']."\n";
    $paring->bind_param("si", $komment2, $_REQUEST['uus_komment']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}*/
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Foto konkurss</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <ul class="menu">
            <li>
                <a href="https://annaoleks24.thkit.ee/php/php/?leht=avaleht.php">Avaleht</a>
            </li>
            <li>
                <a href="fotoKonkurs.php">Adminileht</a>
            </li>
            <li>
                <a href="fotoKonkursPildiValik.php">Kasutajaleht</a>
            </li>
            <li>
                <a href="fotoKonkurssPildiLisamine.php">Pildi lisamine</a>
            </li>
        </ul>
    </nav>
</header>
<h2>Foto konkurss</h2>
<br>
<br>
<table>
    <tr>
        <th>Foto nimetus</th>
        <th>Pilt</th>
        <th>Autor</th>
        <th>Punktid</th>
        <th>Lisamisaeg</th>
        <th>Kommentaarid</th>
        <th>Kustutamine</th>
        <th>Staatus</th>
        <th>Valik</th>
    </tr>

    <?php
    //andmebaasi tabeli kuvamine lehel
    global $yhendus;
    $paring = $yhendus->prepare('SELECT id, fotoNimetus, pilt, autor, punktid, lisamisaeg, kommentaarid, avalik from fotokonkurss');
    $paring->bind_result($id, $fotoNimetus, $pilt, $autor, $punktid, $aeg, $kommentaarid, $avalik);
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        echo "<td>".htmlspecialchars($fotoNimetus)."</td>";
        echo "<td><img src='$pilt' alt='fotoPilt'></td>";
        echo "<td>".$autor."</td>";
        echo "<td>".$punktid."<br><a href='?0punkt=$id'>0 punkti</a></td>";
        echo "<td>".$aeg."</td>";
        echo "<td>".nl2br(htmlspecialchars($kommentaarid))."<br><a href='$_SERVER[PHP_SELF]?kustuta_komment=$id'>Kustuta</a></td>";
/*<form action='?' method='post' id='komen'>
<input type='hidden' name='uus_komment' value='$id'>
<input type='text' name='komment'>
<input type='submit' value='ok'>
</form> </td>";*/
        /*echo "<td><a href='?lisa1punkt=$id'>+1 punkt</a></td>";
        echo "<td><a href='?kustuta1punkt=$id'>-1 punkt</a></td>";*/
        echo "<td><a href='?kustuta=$id'>X</a></td>";
        $tekst="Näita";
        $avaparametr="kuva_id";
        $seis="Peidetud";
        if($avalik==1){
            $tekst="Peida";
            $avaparametr="peida_id";
            $seis="Kuvatud";
        }
        echo "<td>$seis</td>";
        echo "<td><a href='?$avaparametr=$id'>$tekst</a></td>";

        echo "</tr>";
    }
    ?>
</table>

<?php
$yhendus->close();
?>
<?php
include("footer.php");
?>
</body>
</html>