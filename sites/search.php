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
        <section id='allHistory'>
            <h1>Wyniki wyszukiwania</h1>
            <?php
                // Połączenie z bazą danych
                $db = mysqli_connect('localhost', 'root', '', 'magazyn');

                // Sprawdzamy, czy dane zostały przesłane przez AJAX
                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($db, $_GET['search']); // Oczyszczanie danych wejściowych

                    // Zapytanie do bazy danych
                    $query = "SELECT * FROM przedmioty WHERE nazwa LIKE '%$searchTerm%'";
                    $result = mysqli_query($db, $query);
                    
                    // Jeśli są wyniki
                    if (mysqli_num_rows($result) > 0) {
                        // Wyświetlanie wyników w tabeli
                        echo "<table class='normalTable'>";
                        echo "<tr><th>ID</th><th>Nazwa</th><th>Opis</th><th>Kategoria</th><th>Zobacz</th></tr>";
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['nazwa'] . "</td>";
                            echo "<td>" . $row['opis'] . "</td>";
                            echo "<td>" . $row['id_kat'] . "</td>";
                            echo "<td><a href='item_details.php?nazwa=" .$row['nazwa'] . "'>Zobacz</a></td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "<p>Brak wyników dla zapytania: '$searchTerm'</p>";
                    }
                }
            ?>

        </section>
    </main>
    <footer>
    <section id="footer">
        <p>Stronę stworzył: Kajetan Kufieta</p>
        <a href="https://github.com/Kaiito325" target="_blank"><img src="../images/github.png" alt="github" class="icon"></a>
        <a href="https://linkedIn.com/in/kajetan-kufieta-460a23305" target="_blank"><img src="../images/linkedIn.png" alt="github" class="icon"></a>
    </section>
</footer>
</body>