<?php if (isset($_GET['code'])) {die(highlight_file(__FILE__, 1));}?>
<?php
require ('conf.php');

global $yhendus;

//lisamine andmetabelisse
if (isset($_REQUEST["nimetus"])) {
    $nimetus = $_REQUEST['nimetus'];
    $autor = $_REQUEST['autor'];
    $pilt = $_REQUEST['pilt'];
    if (!empty($nimetus) && !empty($pilt)) {
        $paring = $yhendus->prepare("INSERT INTO fotokonkurss(fotoNimetus, autor, pilt, lisamisAeg) VALUES (?, ?, ?, NOW());");
        $paring->bind_param("sss", $nimetus, $autor, $pilt);
        $paring->execute();
        header("Location:$_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "<p style='color:red;'>Foto nimetus ja pildi aadress peavad olema täidetud!</p>";
    }
}

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
                <a href="https://annaoleks24.thkit.ee/PHP/php/?leht=avaleht.php">Avaleht</a>
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
<h4>Foto lisamine hääletamisele</h4>
<form action="?" method="post">
    <label for="nimetus">FotoNimetus</label>
    <input type="text" name="nimetus" id="nimetus" placeholder="Kirjuta ilus foto nimetus">
    <br>
    <label for="autor">Autor</label>
    <input type="text" name="autor" id="autor" placeholder="Autori nimi">
    <br>
    <label for="pilt">Pildifoto</label>
    <textarea name="pilt" id="pilt" cols="30" rows="10">Kopeeri kujutise aadress</textarea>
    <input type="submit" value="Lisa">
</form>

<?php
$yhendus->close();
?>
<?php
include("footer.php");
?>
</body>
</html>