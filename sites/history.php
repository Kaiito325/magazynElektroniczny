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

        if(checkPowerDifferentThan(2)){
            echo "<script>
                alert('Brak uprawnień!');
                window.location.href = '../index.php';
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
            <h1>Cała historia</h1>
            <?php
                $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                $historySelect = "SELECT dziennik_zmian.id, id_uzytkownika, przedmioty.nazwa, akcja, dziennik_zmian.opis, data_zmiany FROM dziennik_zmian INNER JOIN egzemplarze ON egzemplarze.id = dziennik_zmian.id_egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id ORDER BY data_zmiany";
                $historyQuery = mysqli_query($db, $historySelect);
                echo "<table class='normalTable'";
                echo "<tr>  <th>id</th> <th>id użytkownika</th> <th>nazwa przedmiotu</th> <th>akcja</th> <th>opis</th> <th>data zmiany</th> </tr>";
                while($historyRow = mysqli_fetch_row($historyQuery)){
                    echo "<tr>";
                    echo "<td>$historyRow[0]</td> <td>$historyRow[1]</td> <td>$historyRow[2]</td> <td>$historyRow[3]</td> <td>$historyRow[4]</td> <td>$historyRow[5]</td> ";
                    echo "</tr>";
                }
                echo "</table>"
            ?>
        </section>
    </main>
</body>