<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazyn sprzętu elektronicznego</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        session_start();
        include '../functions.php';
        // sprawdza czy jest zalogowany
        if(!checkLogin()){
            echo "<script>
                alert('Zaloguj się!');
                window.location.href = 'panel.php';
            </script>";
        }
        // sprawdza uprawnienia
        if(!checkPowerDifferentThan(0)){
            echo "<script>
                alert('Brak uprawnień!');
                window.location.href = '../index.php';
            </script>";
        }
    ?>
    <!-- menu strony -->
    <menu id="nav">
        <ul>
            <a href="../index.php">
                <li>Strona główna</li>
            </a>
            <a href="items.php">
                <li>Przedmioty</li>
            </a>
            <a href="warehouses.php">
                <li>Magazyny</li>
            </a>
            <a href="panel.php">
                <?php
                    echo "<li>Panel</li>";
                ?>
            </a>
            
            <form action="search.php" method="get">
                <li><input type="text" name="search" id="search" placeholder="🔍    szukaj"></li>
            </form>
            <?php
            if(isset($_SESSION['login'])){
                echo "<a href='logout.php' id='logout-btn'>";
                echo "    <li>🚪 Wyloguj</li>";
                echo "</a>";
            }
            ?>
        </ul>
    </menu>
    <main>
        <?php
        // sprawdza czy dodajemy egzemplarz czy przedmiot
        if(isset($_GET['piece'])){
            echo "<section id='pieceAdd'>";
                $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                echo "<h1>Doaj nowy egzemplarz </h1>";
                // tworzy formularz do egzemplarzu i pobiera do niego dane z bazy danych
                echo "<form action='' method='post' enctype='multipart/form-data' id='editForm'>";
                    echo "<label for='itemName'>Wybierz przedmiot przedmiotu</label>";
                    echo "<select name='itemName' id='itemName'>";
                    $sItem = "SELECT id, nazwa FROM przedmioty ORDER BY id ASC";
                    $qItem = mysqli_query($db, $sItem);
                    while($fItem= mysqli_fetch_row($qItem)){
                        echo "<option value='$fItem[0]'>$fItem[0]. $fItem[1]</option>";
                    }
                    echo '</select>';
                    echo "<label for='warehouseName'>Magazyn</label>";
                    echo "<select name='warehouseName' id='warehouseName'>";
                    $sWarehouse = "SELECT id, nazwa FROM magazyny";
                    $qWarehouse = mysqli_query($db, $sWarehouse);
                    while($fWarehouse = mysqli_fetch_row($qWarehouse)){
                        echo "<option value='$fWarehouse[0]' >$fWarehouse[0]. $fWarehouse[1]</option>";
                    }
                    echo '</select>';
                    echo "<label for='state'>Stan egzemplarzu(y) </label>";
                    echo "<input type='text' name='state' id='state' value='nowy' required>";
                    echo "<label for='description'>Opis egzemplarzu(y) </label>";
                    echo "<input type='text' name='description' id='description' >";
                    echo "<label for='amount'>Ilość egzemplarzu(y)</label>";
                    echo "<input type='number' name='amount' id='amount' value='1'required>";
                    echo "<input type='submit' value='Dodaj'>";
                echo "</form>";
            echo "</section>";
        }else{
            // tworzy formularz do egzemplarzu i pobiera do niego dane z bazy danych
            echo '
                <section id="itemInfo">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="photoContainer">
                            <img id="previewImg" src="../images/product.png" alt="zdjęcie przedmiotu">
                            <br>
                            <label for="photo">Wybierz zdjęcie przedmiotu:</label>
                            <input type="file" name="photo" id="photo" accept="image/*">
                            <br>
                            <input type="checkbox" name="no_photo" id="no_photo" checked>
                            <label for="no_photo">Brak zdjęcia</label>
                        </div>
                        <br>
                        <label for="name">Nazwa przedmiotu: </label>
                        <input type="text" name="name" id="name" required>
                        <br>
                        <label for="description">Opis przedmiotu: </label>
                        <input type="text" name="description" id="description">
                        <br>
                        <label for="category">Wybierz kategorię przedmiotu: </label>
                        <select name="category" id="category">';
                            $s = "SELECT nazwa FROM kategorie;";
                            $q = mysqli_query($db, $s);
                            while($fRow = mysqli_fetch_row($q)){
                                echo "<option value='$fRow[0]'>$fRow[0]</option>";
                            }
                        echo '</select>';
                        echo '<br>
                        <label for="warehouse">Wybierz magazyn: </label>
                        <select name="warehouse" id="warehouse">';
                            $s = "SELECT nazwa FROM magazyny;";
                            $q = mysqli_query($db, $s);
                            while($fRow = mysqli_fetch_row($q)){
                                echo "<option value='$fRow[0]'>$fRow[0]</option>";
                            }
                        echo '</select>';
                        echo '<br>
                        <label for="amount">Ilość: </label>
                        <input type="number" name="amount" id="amount" value="1" min="1">
                        <br>
                        <input type="submit" value="Zapisz">
                    </form>
                </section>';
            }
        ?>
    <!-- zamiana zdjęcia na stronie po wybraniu w formularzu -->
    <script>
    document.getElementById('photo').addEventListener('change', function(event) {
        let reader = new FileReader();
        let noPhotoCheckbox = document.getElementById('no_photo');
    
        if (this.files.length > 0) {
            noPhotoCheckbox.checked = false;
        }
        reader.onload = function(){
            document.getElementById('previewImg').src = reader.result;
        }
        if (event.target.files.length) {
            reader.readAsDataURL(event.target.files[0]);
        }
    });
    </script>
    <!-- zapisywanie danych z formularza do bazy danych -->
    <?php
        $db = mysqli_connect('localhost', 'root', '', 'magazyn');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['photo'])){
                $itemId = substr($_POST['itemName'], 0, 1);
                $warehouseId = substr($_POST['warehouseName'], 0, 1);
                $state = $_POST['state'];
                $description = $_POST['description'];
                $amount = $_POST['amount'];


                //wstawianie do bazy danych egzemplarzu(y)
                if($description == ""){
                    $ins = "INSERT INTO egzemplarze(id_przedmiotu, id_magazynu, stan, ilosc) 
                    VALUES ($itemId, $warehouseId, '$state', $amount)";
                }else{
                    $ins = "INSERT INTO egzemplarze(id_przedmiotu, id_magazynu, stan, opis, ilosc) 
                    VALUES ($itemId, $warehouseId, '$state', '$description', $amount)";
                }
                if (!mysqli_query($db, $ins)) {
                    die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                }else{
                    $pieceId = mysqli_insert_id($db);
                }

                //wstawianie zapisu do dziennika zmian

                $insHistory = "INSERT INTO dziennik_zmian(id_egzemplarze, id_uzytkownika, akcja)
                VALUES ($pieceId, " . $_SESSION['id'] . ", 'dodanie egzemplarzu')";

                if (!mysqli_query($db, $insHistory)) {
                    die("Błąd SQL (dziennik_zmian): " . mysqli_error($db));
                }

                echo "✅ Egzemplarz przedmiotu " . substr($_POST['itemName'], 1) . " został dodany poprawnie!";
            }else{
                $name = trim($_POST["name"]);
                $description = trim($_POST["description"]);
                $category = trim($_POST["category"]);
                $warehouse = trim($_POST["warehouse"]);
                $amount = (int)$_POST["amount"];
                $photoPath = "";

                if (!isset($_POST["no_photo"]) && isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
                    $targetDir = "../upload/";
                    $fileName = uniqid() . "_" . basename($_FILES["photo"]["name"]); // Unikalna nazwa pliku
                    $targetFilePath = $targetDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                    // Dozwolone formaty plików
                    $allowedTypes = array("jpg", "jpeg", "png", "gif");

                    if (in_array($fileType, $allowedTypes)) {
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                            $photoPath = $targetFilePath; // Zapisujemy ścieżkę do bazy
                        } else {
                            echo "Błąd: nie udało się przesłać pliku.";
                        }
                    } else {
                        echo "Nieobsługiwany format pliku.";
                    }
                }

                // Zapis do bazy danych
                
                //pobieranie id kategorii
                $catIdMysql = mysqli_query($db, "SELECT id FROM kategorie WHERE nazwa='$category'");
                if ($catIdMysql && $catId = mysqli_fetch_row($catIdMysql)) {
                    $catId = $catId[0];
                } else {
                    die("Błąd: Nie znaleziono kategorii '$category'.");
                }
                //pobieranie id magazynu
                $warehouseIdMysql = mysqli_query($db, "SELECT id FROM magazyny WHERE nazwa='$warehouse'");
                if ($warehouseIdMysql && $warehouseId = mysqli_fetch_row($warehouseIdMysql)) {
                    $warehouseId = $warehouseId[0];
                } else {
                    die("Błąd: Nie znaleziono magazynu '$warehouse'.");
                }
                //wstawianie do bazy danych przedmiotu
                $ins = "INSERT INTO przedmioty(nazwa, opis, zdjecie, id_kat) 
                VALUES ('$name', '$description', '" . substr($photoPath, 3)."', $catId[0])";
                if (!mysqli_query($db, $ins)) {
                    die("Błąd SQL (przedmioty): " . mysqli_error($db));
                }
                //pobierasnie z bazy danych id tego przedmiotu
                $itemIdMysql = mysqli_query($db, "SELECT id FROM przedmioty WHERE nazwa='$name' ORDER BY id DESC LIMIT 1");
                if ($itemIdMysql && $itemId = mysqli_fetch_row($itemIdMysql)) {
                    $itemId = $itemId[0];
                } else {
                    die("Błąd: Nie znaleziono przedmiotu '$name' po dodaniu.");
                }
                //wstawianie do bazy danych egzemplarzu(y)
                $ins = "INSERT INTO egzemplarze(id_przedmiotu, id_magazynu, ilosc) 
                VALUES ($itemId, $warehouseId, $amount)";
        
                if (!mysqli_query($db, $ins)) {
                    die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                }else{
                    $pieceId = mysqli_insert_id($db);
                }

                //wstawianie zapisu do dziennika zmian

                $insHistory = "INSERT INTO dziennik_zmian(id_egzemplarze, id_uzytkownika, akcja)
                VALUES ($piece, " . $_SESSION['id'] . ", 'dodanie egzemplarzu')";

                if (!mysqli_query($db, $insHistory)) {
                    die("Błąd SQL (dziennik_zmian): " . mysqli_error($db));
                }

                echo "✅ Przedmiot został dodany poprawnie!";
            }
            mysqli_close($db);
        }
    ?>

    </main>
    <footer>
    <section id="footer">
        <p>Stronę stworzył: Kajetan Kufieta</p>
        <a href="https://github.com/Kaiito325" target="_blank"><img src="../images/github.png" alt="github" class="icon"></a>
        <a href="https://linkedIn.com/in/kajetan-kufieta-460a23305" target="_blank"><img src="../images/linkedIn.png" alt="github" class="icon"></a>
    </section>
</footer>
</body>
