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
        <section id="items">
            <h1>Przedmioty</h1>
            <table class="normalTable">

            <?php
                $db = mysqli_connect('localhost','root','','magazyn');
                echo "<tr>";
                echo "<th>przedmiot</th> <th>magazyn</th> <th>ilość</th> <th>szczegóły</th>";
                echo "</tr>";
                $s = "SELECT przedmioty.nazwa, magazyny.nazwa, ilosc FROM egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id INNER JOIN magazyny ON egzemplarze.id_magazynu = magazyny.id ORDER BY przedmioty.nazwa;";
                $q = mysqli_query($db, $s);
                while($fRow = mysqli_fetch_row($q)){
                    echo "<tr>";
                    echo "<td>$fRow[0]</td> <td>$fRow[1]</td> <td>$fRow[2]</td> <td><a href='item_details.php?nazwa=$fRow[0]'>Szczegóły</a></td>";
                    echo "</tr>";
                }
                mysqli_close($db);
            ?>
            </table>
        </section>
        <a href="item_add.php" class="addButton"></a>
    </main>
</body>