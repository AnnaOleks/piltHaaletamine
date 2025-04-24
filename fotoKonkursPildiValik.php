<?php if (isset($_GET['code'])) {die(highlight_file(__FILE__, 1));}?>
<?php
require('conf.php');
global $yhendus;

// Удаление записи
/*if (isset($_REQUEST["kustuta"])) {
    $paring = $yhendus->prepare("DELETE FROM fotokonkurss WHERE id = ?");
    $paring->bind_param("i", $_REQUEST['kustuta']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}

// Добавление новой записи
if (isset($_POST["nimetus"]) && !isset($_POST["muuda_id"])) {
    $paring = $yhendus->prepare("INSERT INTO fotokonkurss(fotoNimetus, autor, pilt, lisamisAeg) VALUES (?, ?, ?, NOW())");
    $paring->bind_param("sss", $_POST['nimetus'], $_POST['autor'], $_POST['pilt']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}*/

// Обновление записи после изменения
if (isset($_POST["muuda_id"])) {
    $paring = $yhendus->prepare("UPDATE fotokonkurss SET fotoNimetus=?, autor=?, pilt=?, lisamisAeg=NOW(), kommentaarid=? WHERE id=?");
    $paring->bind_param("ssssi", $_POST['nimetus'], $_POST['autor'], $_POST['pilt'], $_POST['kommentaarid'], $_POST['muuda_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}

// +1 пункт
if (isset($_REQUEST["lisa1punkt"])) {
    $paring = $yhendus->prepare("UPDATE fotokonkurss SET punktid = punktid + 1 WHERE id = ?");
    $paring->bind_param("i", $_REQUEST['lisa1punkt']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]?id=$_REQUEST[lisa1punkt]");
    exit();
}

// -1 пункт
if (isset($_REQUEST["kustuta1punkt"])) {
    // Получаем текущее количество пунктов
    $paring = $yhendus->prepare("SELECT punktid FROM fotokonkurss WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["kustuta1punkt"]);
    $paring->execute();
    $paring->bind_result($punktid);
    $paring->fetch();
    $paring->close();

    // Проверка, можно ли уменьшить
    if ($punktid > 0) {
        $paring = $yhendus->prepare("UPDATE fotokonkurss SET punktid = punktid - 1 WHERE id = ?");
        $paring->bind_param("i", $_REQUEST['kustuta1punkt']);
        $paring->execute();
    }

    header("Location: $_SERVER[PHP_SELF]?id=" . $_REQUEST['kustuta1punkt']);
    exit();
}

// Добавление нового комментария
if(isSet($_REQUEST["uus_komment"]) && !empty($_REQUEST["komment"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET kommentaarid=Concat(kommentaarid, ?) WHERE id=?");
    $komment2=$_REQUEST['komment']."\n";
    $paring->bind_param("si", $komment2, $_REQUEST['uus_komment']);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]?id=$_REQUEST[uus_komment]");
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
<div id="piltidenimekiri">
    <h4>Pildid</h4>
    <ul>
        <?php
        $kask = $yhendus->prepare("SELECT id, fotoNimetus FROM fotokonkurss WHERE avalik=1");
        $kask->bind_result($id, $fotoNimetus);
        $kask->execute();
        while ($kask->fetch()) {
            echo "<li><a href='$_SERVER[PHP_SELF]?id=$id'>" . htmlspecialchars($fotoNimetus) . "</a></li>";
        }
        ?>
    </ul>
</div>

<div id="pildiinfo">
    <?php
    // Показ инфо о картинке
    if (isset($_REQUEST["id"])) {
        $kask = $yhendus->prepare("SELECT id, fotoNimetus, pilt, autor, punktid, lisamisAeg, kommentaarid FROM fotokonkurss WHERE id=?");
        $kask->bind_param("i", $_REQUEST["id"]);
        $kask->bind_result($id, $fotoNimetus, $pilt, $autor, $punktid, $aeg, $kommentaarid);
        $kask->execute();
        if ($kask->fetch()) {
            echo "<h4>" . htmlspecialchars($fotoNimetus) . "</h4>";
            echo "<br><img src='$pilt' height='200' width='200' alt='fotoPilt'>";
            echo "<br>Punkte kokku: " . htmlspecialchars($punktid);
            echo "<br><a href='?lisa1punkt=$id'>+1 punkt</a>";
            echo "<a href='?kustuta1punkt=$id'>   -1 punkt</a>";
            echo "<br><br>Autor: " . htmlspecialchars($autor);
            echo "<br>Pilt lisatud: " . htmlspecialchars($aeg);
            echo "<br><br>Arvamused: " . nl2br(htmlspecialchars($kommentaarid)) . "<br>";
            echo '<form action="?" method="post">
                    <input type="hidden" name="uus_komment" value="' . $id . '">
                    <input type="text" name="komment">
                    <input type="submit" value="Lisa kommentaar">
                  </form>';

        }
    }

    // Форма для добавления новой записи
    /*if (isset($_REQUEST["lisamine"])) {
        echo '
        <h3>Lisa uus foto</h3>
        <form action="?" method="post">
            <label for="nimetus">Foto nimetus:</label><br>
            <input type="text" name="nimetus" id="nimetus" placeholder="Kirjuta ilus foto nimetus"><br>
            <label for="autor">Autor:</label><br>
            <input type="text" name="autor" id="autor" placeholder="Autori nimi"><br>
            <label for="pilt">Pildi URL:</label><br>
            <textarea name="pilt" id="pilt" cols="30" rows="5" placeholder="Kopeeri pildi aadress"></textarea><br>
            <input type="submit" value="Lisa foto">
        </form>';
    }*/

    // Форма для изменения существующей записи
    /*if (isset($_GET["muuda"])) {
        $kask = $yhendus->prepare("SELECT id, fotoNimetus, pilt, autor, kommentaarid FROM fotokonkurss WHERE id=?");
        $kask->bind_param("i", $_GET["muuda"]);
        $kask->bind_result($id, $fotoNimetus, $pilt, $autor, $kommentaarid);
        $kask->execute();
        if ($kask->fetch()) {
            echo '
            <h3>Muuda foto andmeid</h3>
        <form action="?" method="post">
            <input type="hidden" name="muuda_id" value="' . htmlspecialchars($id) . '">
            <label for="nimetus">Foto nimetus:</label><br>
            <input type="text" name="nimetus" id="nimetus" value="' . htmlspecialchars($fotoNimetus) . '"><br>
            <label for="autor">Autor:</label><br>
            <input type="text" name="autor" id="autor" value="' . htmlspecialchars($autor) . '"><br>
            <label for="pilt">Pildi URL:</label><br>
            <textarea name="pilt" id="pilt" cols="30" rows="3">' . htmlspecialchars($pilt) . '</textarea><br>
            <label for="kommentaarid">Kommentaarid:</label><br>
            <textarea name="kommentaarid" id="kommentaarid" cols="30" rows="3">' . htmlspecialchars($kommentaarid) . '</textarea><br>
            <input type="submit" value="Muuda">
        </form>';
        }
    }*/
    ?>
</div>
<?php
$yhendus->close();
?>
</body>
</html>
