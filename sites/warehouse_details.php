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
            <a href="history.php">
                <li>Historia</li>
            </a>
            
            <li><input type="text" name="search" id="search" placeholder="🔍    szukaj"></li>
        </ul>
    </menu>
    <main>
        <section class="availableItems">
            <h1>Dostępne egzemplarze przedmiotu</h1>
            <table class="normalTable">
                <?php
                    $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                    echo "<tr>";
                    echo "<th>przedmiot</th> <th>magazyn</th> <th>lokalizacja magazynu</th> <th>ilość</th> <th>data dodania</th> <th>Edytuj</th>";
                    echo "</tr>";
                    $s = "SELECT przedmioty.nazwa, magazyny.nazwa, magazyny.lokalizacja, ilosc, data_dodania FROM egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id INNER JOIN magazyny ON egzemplarze.id_magazynu = magazyny.id WHERE przedmioty.nazwa='". $_GET['nazwa']."'";
                    $q = mysqli_query($db, $s);
                    while($fRow = mysqli_fetch_row($q)){
                        echo "<tr>";
                        echo "<td>$fRow[0]</td> <td>$fRow[1]</td> <td>$fRow[2]</td> <td>$fRow[3]</td> <td>$fRow[4]</td>  <td><a href='item_details.php?nazwa=$fRow[0]'>Edytuj</a></td>";
                        echo "</tr>";
                    }   
                ?>
            </table>
        </section>
        <section id="itemInfo">
            <button id="editButton" onclick="toggleForm()">Edytuj przedmiot</button>
            <br>

            <form action="" method="post" enctype="multipart/form-data" id="editForm" style="display: none;">
                <div class="photoContainer">
                    <?php
                        $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                        $s = "SELECT przedmioty.nazwa, opis, zdjecie, kategorie.nazwa FROM przedmioty INNER JOIN kategorie ON przedmioty.id_kat = kategorie.id WHERE przedmioty.nazwa = '" .$_GET['nazwa'] ."'";
                        $q = mysqli_query($db, $s);
                        $fRow = mysqli_fetch_row($q);
                        if($fRow[2] == "" || $fRow[2] =="null"){
                            echo "<img id='previewImg' src='../images/product.png' alt='zdjęcie przedmiotu'>";
                        }else{
                            echo  "<img id='previewImg' src='../$fRow[2]' alt='zdjęcie przedmiotu'>";
                        }

                    ?>
                    <br>
                    <label for="photo">Wybierz zdjęcie przedmiotu:</label>
                    <input type="file" name="photo" id="photo" accept="image/*">
                    <br>
                    <input type="checkbox" name="no_photo" id="no_photo">
                    <label for="no_photo">Brak zdjęcia</label>
                </div>
                <br>
                <label for="name">Nazwa przedmiotu: </label>
                <?php
                    if($fRow[0] == "" || $fRow[0] =="null"){
                        echo "<input type='text' name='name' id='name' required>";
                    }else{
                        echo "<input type='text' name='name' id='name' value='$fRow[0]' required>";
                    }
                ?>
                <br>
                <label for="description">Opis przedmiotu: </label>
                <?php
                    if($fRow[1] == "" || $fRow[1] =="null"){
                        echo "<input type='text' name='description' id='description'>";
                    }else{
                        echo "<input type='text' name='description' id='description' value='$fRow[1]'>";
                    }
                ?>
                <br>
                <label for="category">Wybierz kategorię przedmiotu: </label>
                <select name="category" id="category">
                    <?php
                        $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                        $s = "SELECT nazwa FROM kategorie;";
                        $q = mysqli_query($db, $s);
                        while($fRow2 = mysqli_fetch_row($q)){
                            if($fRow[3] == $fRow2[0])
                                echo "<option value='$fRow2[0]' selected>$fRow2[0]</option>";
                            else
                            echo "<option value='$fRow2[0]'>$fRow2[0]</option>";
                        }
                    ?>
                </select>
                <br>
                <input type="submit" value="Zapisz">
            </form>
        </section>
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
                //wstawianie do bazy danych egzemplarzu(y)
                $ins = "INSERT INTO egzemplarze(id_przedmiotu, id_magazynu, ilosc) 
                VALUES ($itemId, $warehouseId, $amount)";
        
                if (!mysqli_query($db, $ins)) {
                    die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                }

                echo "✅ Przedmiot został dodany poprawnie!";
                mysqli_close($db);
            }
        ?>
    </main>
</body>