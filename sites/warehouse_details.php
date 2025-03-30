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
            <h1>Dostępne egzemplarze przedmiotów w magazynie</h1>
            <table class="normalTable">
                <?php
                    $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                    echo "<tr>";
                    echo "<th>przedmiot</th> <th>ilość</th> <th>data dodania</th>";
                    echo "<th>Szczegóły</th>";
                    echo "</tr>";
                    $s = "SELECT przedmioty.nazwa, ilosc, data_dodania FROM egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id INNER JOIN magazyny ON egzemplarze.id_magazynu = magazyny.id WHERE magazyny.nazwa='". $_GET['nazwa']."'";
                    $q = mysqli_query($db, $s);
                    while($fRow = mysqli_fetch_row($q)){
                        echo "<tr>";
                        echo "<td>$fRow[0]</td> <td>$fRow[1]</td> <td>$fRow[2]</td> "; 
                        echo "<td><a href='item_details.php?nazwa=$fRow[0]'>Szczegóły</a></td>";
                        echo "</tr>";
                    }   
                ?>
            </table>
        </section>
        <?php
        if(checkPowerDifferentThan(0)){
            echo "<section id='itemInfo'>";
                echo "<button id='editButton' onclick='toggleForm()'>Edytuj Magazyn</button>";
                echo "<br>";
                echo "<form action='' method='post' enctype='multipart/form-data' id='editForm' style='display: none;'>";
                    echo "<div class='photoContainer'>";
                        $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                        $s = "SELECT nazwa, zdjecie, lokalizacja FROM magazyny WHERE magazyny.nazwa = '" .$_GET['nazwa'] ."'";
                        $q = mysqli_query($db, $s);;
                        $fRow = mysqli_fetch_row($q);;
                        if($fRow[2] == "" || $fRow[2] =="null"){
                        echo "<img id='previewImg' src='../images/product.png' alt='zdjęcie magazynu'>";
                        }else{
                        echo  "<img id='previewImg' src='../$fRow[1]' alt='zdjęcie magazynu'>";
                        }
                        echo "<br>";
                        echo "<label for='photo'>Wybierz zdjęcie magazynu:</label>";
                        echo "<input type='file' name='photo' id='photo' accept='image/*'>";
                        echo "<br>";
                        echo "<input type='checkbox' name='no_photo' id='no_photo' checked>";
                        echo "<label for='no_photo'>Brak zdjęcia</label>";
                    echo "</div>";
                echo "<br>";
                echo "<label for='name'>Nazwa magazynu: </label>";
                if($fRow[0] == "" || $fRow[0] =="null"){
                    echo "<input type='text' name='name' id='name' required>";
                }else{
                echo "<input type='text' name='name' id='name' value='$fRow[0]' required>";
                }
                echo "<br>";
                echo "<label for='location'>lokalizacja: </label>";
                if($fRow[1] == "" || $fRow[1] =="null"){
                echo "<input type='text' name='location' id='location'>";
                }else{
                echo "<input type='text' name='location' id='location' value='$fRow[2]'>";
                };
                echo "<br>";
                echo "<input type='submit' value='Zapisz'>";
                echo "</form>";
            echo "</section>";
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
                $name = trim($_POST["name"]);
                $location = trim($_POST["location"]);
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
                //wstawienie do bazy danych nowych danych o magazynie
                if($photoPath == ""){
                    $upd = "UPDATE magazyny SET nazwa = '$name', lokalizacja = '$location' WHERE nazwa = '" . $_GET['nazwa'] . "'";
                }else{
                    $upd = "UPDATE magazyny SET nazwa = '$name', zdjecie = '$photoPath', lokalizacja = '$location' WHERE nazwa = '" . $_GET['nazwa'] . "'";
                }
        
                if (!mysqli_query($db, $upd)) {
                    die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                }

                echo "✅ magazyn został edytowany poprawnie!";
                mysqli_close($db);
            }
        ?>
    </main>
</body>