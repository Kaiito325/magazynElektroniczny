<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazyn sprzętu elektronicznego</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        session_start();
        include 'functions.php';

        if(!checkLogin()){
            echo "<script>
                alert('Zaloguj się!');
                window.location.href = 'sites/panel.php';
            </script>";
        }
    ?>
    <!-- menu strony -->
    <menu id="nav">
        <ul>
            <a href="index.php">
                <li>Strona główna</li>
            </a>
            <a href="sites/items.php">
                <li>Przedmioty</li>
            </a>
            <a href="sites/warehouses.php">
                <li>Magazyny</li>
            </a>
            <a href="sites/panel.php">
                <?php
                    echo "<li>Panel</li>";
                ?>
            </a>
            
            <form action="sites/search.php" method="get">
                <li><input type="text" name="search" id="search" placeholder="🔍    szukaj"></li>
            </form>
            <?php
                if(isset($_SESSION['login'])){
                    echo "<a href='sites/logout.php' id='logout-btn'>";
                    echo "    <li>🚪 Wyloguj</li>";
                    echo "</a>";
                }
            ?>
        </ul>
    </menu>
    <!-- Główna część strony -->
    <main>
        <!-- Wyświetla przedmioty z największą ilością egzemplarzy dostępnych we wszystkich magazynach -->
        <section id="items">
            <h2>Przedmioty z największą ilością egzemplarzy</h2>
            <div class="carousel">
                <button class="prev">&#10094;</button>
                <div class="itemHolder">
                    <?php 
                        $db = mysqli_connect('localhost', 'root','', 'magazyn');
                        $s = "SELECT nazwa, SUM(ilosc), zdjecie FROM egzemplarze INNER JOIN przedmioty ON(egzemplarze.id_przedmiotu = przedmioty.id) GROUP BY przedmioty.nazwa ORDER BY SUM(ilosc) DESC LIMIT 10;";
                        $q = mysqli_query($db, $s);
                        while($fRow = mysqli_fetch_row($q)){
                            echo "<a href='sites/item_details.php?nazwa=$fRow[0]'><div class='item'>";
                            echo '<div class="gallery">';
                            if($fRow[2] == "") {
                                echo "<img class='gallery-image' src='images/product.png' alt='ad'>";
                            } else {
                                echo "<img class='gallery-image' src='$fRow[2]' alt='zdjecie'>";
                            }
                            echo "</div>";
                            echo "<h2>$fRow[0]</h2>";
                            echo "<h3>Ilość: $fRow[1]</h3>";
                            echo "</a></div>";
                            }
                        ?>
                </div>
                <button class="next">&#10095;</button>
            </div>
        </section>

        <!-- Wyświetla maagzyny z największą ilością przedmiotów w nich -->
        <section id="warehouses">
            <h2>Największe magazyny</h2>
            <div class="itemHolder">
                <?php
                $s = "SELECT nazwa, lokalizacja, zdjecie FROM magazyny INNER JOIN egzemplarze ON magazyny.id = egzemplarze.id_magazynu GROUP BY id_magazynu ORDER BY COUNT(id_magazynu) DESC LIMIT 10;";
                $q = mysqli_query($db, $s);
                while($fRow = mysqli_fetch_row($q)){
                    echo "<a href='sites/warehouse_details.php?nazwa=$fRow[0]'><div class='warehouse item'>";
                    echo '<div class="gallery">';
                    if($fRow[2] == "") {
                        echo "<img class='gallery-image' src='images/warehouse.png' alt='ad'>";
                    } else {
                        echo "<img class='gallery-image' src='$fRow[2]' alt='zdjecie'>";
                    }
                    echo "</div>";
                    echo "<h2>$fRow[0]</h2>";
                    echo "<h3>Lokalizacja: ";
                    if($fRow[1] != "")
                        echo "$fRow[1]</h3>";
                    else
                        echo "brak </h3>";
                    echo "</a></div>";
                    }
                    
                ?>
            </div>
        </section>
        <!-- Pokazuje ostatnie zmiany w magazynie -->
         <?php
         if(isset($_SESSION['power'])){
            if($_SESSION['power'] == '2'){
                echo "<section id='history'>";
                echo "<h2>Ostatnie zmiany</h2>";
                echo "<div class='itemHolder'>";
                $s = "SELECT akcja, id_egzemplarze, przedmioty.nazwa FROM dziennik_zmian INNER JOIN egzemplarze ON dziennik_zmian.id_egzemplarze = egzemplarze.id 
                    INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id ORDER BY data_zmiany DESC LIMIT 4;";
                $q = mysqli_query($db, $s);
                while($fRow = mysqli_fetch_row($q)){
                    echo "<a href='sites/item_details.php?nazwa=$fRow[2]'><div class='dataLog item'>";
                    echo "<h2>$fRow[0]</h2>";
                    echo "<h3>id egzemplarza: ";
                    echo "$fRow[1]</h3>";
                    echo "</a></div>";
                    }
            echo "</div>";
                echo "</section>";
            }
         }
         mysqli_close($db);
        ?>
    </main>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    const itemHolder = document.querySelector('.itemHolder');
    const items = document.querySelectorAll('.item');
    
    let currentIndex = 0; // Indeks bieżącego widocznego elementu
    const visibleItems = 4; // Ilość widocznych przedmiotów na raz
    const totalItems = items.length;  // Całkowita liczba elementów
    
    // Funkcja do obliczania szerokości pojedynczego elementu
    function updateItemWidth() {
        const itemWidth = items[0].offsetWidth + parseInt(window.getComputedStyle(items[0]).marginRight); // Oblicz szerokość elementu z marginem
        const totalWidth = itemWidth * totalItems;  // Całkowita szerokość dla wszystkich elementów

        itemHolder.style.width = `${totalWidth}px`;  // Zaktualizuj szerokość itemHolder na podstawie elementów
    }

    // Funkcja do przesuwania w lewo
    function moveLeft() {
        if (currentIndex > 0) {
            currentIndex--;
            updateItemWidth();
            itemHolder.style.transform = `translateX(-${currentIndex * items[0].offsetWidth}px)`;
        }
    }

    // Funkcja do przesuwania w prawo
    function moveRight() {
        if (currentIndex < totalItems - visibleItems) {
            currentIndex++;
            updateItemWidth();
            itemHolder.style.transform = `translateX(-${currentIndex * items[0].offsetWidth}px)`;
        }
    }

    // Funkcja aktualizująca rozmiar okna
    window.addEventListener('resize', () => {
        updateItemWidth();  // Zaktualizuj szerokość elementów po zmianie rozmiaru okna
    });

    // Inicjalizuj szerokość przedmiotów
    updateItemWidth();

    prevButton.addEventListener('click', moveLeft);
    nextButton.addEventListener('click', moveRight);
});

    </script>
</body>
<footer>
    <section id="footer">
        <p>Stronę stworzył: Kajetan Kufieta</p>
        <a href="https://github.com/Kaiito325" target="_blank"><img src="images/github.png" alt="github" class="icon"></a>
        <a href="https://linkedIn.com/in/kajetan-kufieta-460a23305" target="_blank"><img src="images/linkedIn.png" alt="github" class="icon"></a>
    </section>
</footer>
</html>