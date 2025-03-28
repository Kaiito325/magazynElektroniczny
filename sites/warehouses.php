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
        <section id="items">
            <h1>Magazyny</h1>
            <table class="normalTable">

            <?php
                $db = mysqli_connect('localhost','root','','magazyn');
                echo "<tr>";
                echo "<th>nazwa</th> <th>lokalizacja</th> <th>szczegóły</th>";
                echo "</tr>";
                $s = "SELECT nazwa, lokalizacja FROM magazyny;";
                $q = mysqli_query($db, $s);
                while($fRow = mysqli_fetch_row($q)){
                    echo "<tr>";
                    echo "<td>$fRow[0]</td> <td>$fRow[1]</td> <td><a href='warehouse_details.php?nazwa=$fRow[0]'>Szczegóły</a></td>";
                    echo "</tr>";
                }
                mysqli_close($db);
            ?>
            </table>
        </section>
        <?php
            if(checkPowerDifferentThan(0)){
                echo "<a href='warehouse_add.php' class='addButton'></a>";
            }
        ?>
    </main>
</body>