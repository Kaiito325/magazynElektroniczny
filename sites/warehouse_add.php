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
            
            <li><input type="text" name="search" id="search" placeholder="🔍    szukaj"></li>
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
    <section id="itemInfo">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="photoContainer">
                <img id="previewImg" src="../images/warehouse.png" alt="zdjęcie magazynu">
                <br>
                <label for="photo">Wybierz zdjęcie magazynu:</label>
                <input type="file" name="photo" id="photo" accept="image/*">
                <br>
                <input type="checkbox" name="no_photo" id="no_photo" checked>
                <label for="no_photo">Brak zdjęcia</label>
            </div>
            <br>
            <label for="name">Nazwa magazynu: </label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="location">lokalizacja magazynu: </label>
            <input type="text" name="location" id="location">
            <br>
            <input type="submit" value="Zapisz">
        </form>
    </section>
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
            // Zapis do bazy danych
            
            //wstawianie do bazy danych magazynu
            $ins = "INSERT INTO magazyny(nazwa, zdjecie, lokalizacja) 
            VALUES ('$name', '" . substr($photoPath, 3) ."', '$location')";
    
            if (!mysqli_query($db, $ins)) {
                die("Błąd SQL (egzemplarze): " . mysqli_error($db));
            }

            echo "✅ Magazyn został dodany poprawnie!";
            mysqli_close($db);
        }
    ?>

    </main>
</body>