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

        if(!checkLogin()){
            echo "<script>
                alert('Zaloguj się!');
                window.location.href = 'panel.php';
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
        <section class="availableItems">
            <?php
                $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                if(isset($_GET['nazwa'])){
                    echo "<h1>Dostępne egzemplarze przedmiotu: <i style='color: #666;'>". $_GET['nazwa']." </i></h1>";
                    echo "<table class='normalTable'>";
                        $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                        echo "<tr>";
                        echo "<th>id</th> <th>magazyn</th> <th>lokalizacja magazynu</th> <th>stan</th> <th>opis</th> <th>ilość</th> <th>data dodania</th> ";
                        if(checkPowerDifferentThan(0)){
                            echo "<th>Edytuj</th>";
                        }
                        echo "</tr>";
                        $s = "SELECT egzemplarze.id, magazyny.nazwa, magazyny.lokalizacja, stan, egzemplarze.opis, ilosc, data_dodania FROM egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id INNER JOIN magazyny ON egzemplarze.id_magazynu = magazyny.id WHERE przedmioty.nazwa='". $_GET['nazwa']."'";
                        $q = mysqli_query($db, $s);
                        while($fRow = mysqli_fetch_row($q)){
                            echo "<tr>";
                            echo "<td>$fRow[0]</td> <td>$fRow[1]</td> <td>$fRow[2]</td> <td>$fRow[3]</td> <td>$fRow[4]</td> <td>$fRow[5]</td> <td>$fRow[6]</td> ";
                            if($_SESSION['power'] !=0){
                                echo "<td><a href='item_details.php?id=$fRow[0]'>Edytuj</a></td>";
                            }
                            echo "</tr>";
                        }   
                        echo "</table>";
                }else if($_GET['id']){
                    $s = "SELECT przedmioty.nazwa, magazyny.nazwa, stan, egzemplarze.opis, ilosc, data_dodania FROM egzemplarze INNER JOIN przedmioty on egzemplarze.id_przedmiotu = przedmioty.id INNER JOIN magazyny ON egzemplarze.id_magazynu = magazyny.id WHERE egzemplarze.id = " . $_GET['id'] . "";
                    $q = mysqli_query($db, $s);
                    $fRow = mysqli_fetch_row($q);
                    echo "<h1>Edyjcja egzemplarzu przedmiotu: <i style='color: #666;'>$fRow[0]</i> o ID: <i style='color: #666;'>" .$_GET['id'] . "</i> </h1>";
                    echo "<form action='' method='post' enctype='multipart/form-data' id='editForm'>";
                    echo "<label for='warehouseName'>Magazyn</label>";
                    echo "<select name='warehouseName' id='warehouseName'>";
                    $sWarehouse = "SELECT id, nazwa FROM magazyny";
                    $qWarehouse = mysqli_query($db, $sWarehouse);
                    while($fWarehouse = mysqli_fetch_row($qWarehouse)){
                        if($fWarehouse[1] == $fRow[1])
                            echo "<option value='$fWarehouse[0]' selected>$fWarehouse[0]. $fWarehouse[1]</option>";
                        else
                            echo "<option value='$fWarehouse[0]' >$fWarehouse[0]. $fWarehouse[1]</option>";
                    }
                    echo '</select>';
                    echo "<label for='state'>Stan egzemplarz" . (($fRow[4] <= 1)? 'u':'y'). "</label>";
                    echo "<input type='text' name='state' id='state' value='$fRow[2]'>";
                    echo "<label for='description'>Opis egzemplarz" . (($fRow[4] <= 1)? 'u':'y'). "</label>";
                    echo "<input type='text' name='description' id='description' value='$fRow[3]'>";
                    echo "<label for='amount'>Ilość egzemplarz" . (($fRow[4] <= 1)? 'u':'y'). "</label>";
                    echo "<input type='number' name='amount' id='amount' value='$fRow[4]'>";
                    echo "<label for='date'>Data dodania<label><br>";
                    $date = substr($fRow[5], 0,10);
                    $dateTime = substr($fRow[5],11, 5);
                    echo "<input type='date' name='date' id='date' class='dateTime' value='$date'>";
                    echo "<input type='time' name='time' id='time' class='dateTime' value='$dateTime'>";
                    echo "<input type='submit' value='Edytuj'>";
                    echo "</form>";
                }
            ?>
        </section>
        <?php 
            if(isset($_GET['nazwa'])){
                if(checkPowerDifferentThan(0)){

                    echo "<section id='itemInfo'>";
                    
                    echo "<button id='editButton' onclick='toggleForm()'>Edytuj przedmiot</button>";
                    echo "<br>";
                    
                    echo "<form action='' method='post' enctype='multipart/form-data' id='editForm' style='display: none;'>";
                    echo "<div class='photoContainer'>";
                    $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                    $s = "SELECT przedmioty.nazwa, opis, zdjecie, kategorie.nazwa FROM przedmioty INNER JOIN kategorie ON przedmioty.id_kat = kategorie.id WHERE przedmioty.nazwa = '" .$_GET['nazwa'] ."'";
                    $q = mysqli_query($db, $s);
                    $fRow = mysqli_fetch_row($q);
                    if($fRow[2] == "" || $fRow[2] =="null"){
                        echo "<img id='previewImg' src='../images/product.png' alt='zdjęcie przedmiotu'>";
                    }else{
                        echo  "<img id='previewImg' src='../$fRow[2]' alt='zdjęcie przedmiotu'>";
                    }
                    echo "<br>";
                    echo "<label for='photo'>Wybierz zdjęcie przedmiotu:</label>";
                    echo "<input type='file' name='photo' id='photo' accept='image/*'";
                    echo "<br>";
                    echo "<input type='checkbox' name='no_photo' id='no_photo' checked>";
                    echo "<label for='no_photo'>Brak zdjęcia</label>";
                    echo "</div>";
                    echo "<br>";
                    echo "<label for='name'>Nazwa przedmiotu: </label>";
                    if($fRow[0] == "" || $fRow[0] =="null"){
                        echo "<input type='text' name='name' id='name' required>";
                    }else{
                        echo "<input type='text' name='name' id='name' value='$fRow[0]' required>";
                    }
                    echo "<br>";
                    echo "<label for='description'>Opis przedmiotu: </label>";
                    if($fRow[1] == "" || $fRow[1] =="null"){
                        echo "<input type='text' name='description' id='description'>";
                    }else{
                        echo "<input type='text' name='description' id='description' value='$fRow[1]'>";
                    }
                    echo "<br>";
                    echo "<label for='category'>Wybierz kategorię przedmiotu: </label>";
                    echo "<select name='category' id='category'>";
                    $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                    $s = "SELECT nazwa FROM kategorie;";
                    $q = mysqli_query($db, $s);
                    while($fRow2 = mysqli_fetch_row($q)){
                        if($fRow[3] == $fRow2[0])
                        echo "<option value='$fRow2[0]' selected>$fRow2[0]</option>";
                    else
                    echo "<option value='$fRow2[0]'>$fRow2[0]</option>";
                    }   
                
                    echo "</select>";
                    echo "<br>";
                    echo "<input type='submit' value='Zapisz'>";
                    echo "</form>";
                    echo "</section>";
                }
            }
            ?>
            <script>
            function toggleForm() {
                var form = document.getElementById('editForm');
                if (form.style.display === 'none') {
                    form.style.display = 'block'; // Pokazuje formularz
                } else {
                    form.style.display = 'none'; // Ukrywa formularz
                    }
                }
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
        <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_GET['id'])){
                    $id = $_GET['id'];

                    $q = "SELECT id_przedmiotu, id_magazynu, stan, opis, ilosc, data_dodania FROM egzemplarze WHERE id = $id";
                    $result = mysqli_query($db, $q);
                    $currentData = mysqli_fetch_assoc($result);

                    $warehouseId = substr($_POST['warehouseName'], 0, 1);
                    $state = $_POST['state'];
                    $description = $_POST['description'];
                    $amount = $_POST['amount'];
                    $date = $_POST['date'];
                    $time = $_POST['time'];
                    $fullDate = $date . " " . $time . ":00";

                    $changes = [];
                    $changesDescription = [];

                    if ($currentData['id_magazynu'] != $warehouseId) {
                        $changes[] = "id_magazynu='$warehouseId'";
                        $changesDescription[] = "Magazyn: {$currentData['id_magazynu']} → $warehouseId";
                    }
                    if ($currentData['stan'] != $state) {
                        $changes[] = "stan='$state'";
                        $changesDescription[] = "Stan: {$currentData['stan']} → $state";
                    }
                    if ($currentData['opis'] != $description) {
                        $changes[] = "opis='$description'";
                        $changesDescription[] = "Opis zmieniony";
                    }
                    if ($currentData['ilosc'] != $amount) {
                        $changes[] = "ilosc='$amount'";
                        $changesDescription[] = "Ilość: {$currentData['ilosc']} → $amount";
                    }
                    if (substr($currentData['data_dodania'], 0, 16) != substr($fullDate, 0, 16)) {
                        $changes[] = "data_dodania='$fullDate'";
                        $changesDescription[] = "Data dodania: {$currentData['data_dodania']} → $fullDate";
                    }

                    $descriptionText = implode(", ", $changesDescription);

                    //edycja egzemplarzu
                    $upd = "UPDATE egzemplarze SET " . implode(", ", $changes) . " WHERE id='$id'";
                    if (!mysqli_query($db, $upd)) {
                        die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                    }

                    //wstawianie zapisu do dziennika zmian
                    if (!empty($descriptionText)) {
                        // Tworzymy zapytanie SQL z opisem zmian
                        $insHistory = "INSERT INTO dziennik_zmian(id_egzemplarze, id_uzytkownika, akcja, opis)
                                       VALUES ('$id', '" . $_SESSION['id'] . "', 'Edycja egzemplarza', '" . mysqli_real_escape_string($db, $descriptionText) . "')";
                    
                        if (!mysqli_query($db, $insHistory)) {
                            die("Błąd SQL (dziennik_zmian): " . mysqli_error($db));
                        }
                    }
                    
                    echo "<script>
                            alert('✅ Egzemplarz został edytowany!');
                            window.location.href = '../index.php';
                        </script>";
                    mysqli_close($db);
                }else if(isset($_GET['nazwa'])){

                    $name = trim($_POST["name"]);
                    $description = trim($_POST["description"]);
                    $category = trim($_POST["category"]);
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
                    //wstawianie do bazy danych przedmiotu(y)
                    if($photoPath == ""){
                        $upd = "UPDATE przedmioty SET nazwa = '$name', opis = '$description', id_kat ='$catId[0]' WHERE nazwa = '$name'";                        
                    }else {
                        $upd = "UPDATE przedmioty SET nazwa = '$name', opis = '$description', zdjecie ='$photoPath', id_kat ='$catId[0]' WHERE nazwa = '$name'";
                    }                    
                    if (!mysqli_query($db, $upd)) {
                        die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                    }
                    
                    echo "✅ Przedmiot został edytowany!";
                    mysqli_close($db);
                }
            }
        ?>
    </main>
</body>