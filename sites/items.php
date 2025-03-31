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
        <section id="items">
            <h1>Przedmioty</h1>
            <table class="normalTable">

            <?php
                $db = mysqli_connect('localhost','root','','magazyn');
                echo "<tr>";
                echo "<th>id</th> <th>przedmiot</th> <th>magazyn</th> <th>ilość</th> <th>szczegóły</th>";
                echo "</tr>";
                $s = "SELECT przedmioty.id, przedmioty.nazwa, GROUP_CONCAT(DISTINCT magazyny.nazwa), SUM(ilosc) FROM egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id INNER JOIN magazyny ON egzemplarze.id_magazynu = magazyny.id GROUP BY przedmioty.nazwa ORDER BY przedmioty.id ;";
                $q = mysqli_query($db, $s);
                while($fRow = mysqli_fetch_row($q)){
                    echo "<tr>";
                    echo "<td>$fRow[0]</td> <td>$fRow[1]</td> <td>$fRow[2]</td> <td>$fRow[3]</td> <td><a href='item_details.php?nazwa=$fRow[1]'>Szczegóły</a></td>";
                    echo "</tr>";
                }
                mysqli_close($db);
            ?>
            </table>
        </section>
        <?php
            if(checkPowerDifferentThan(0)){
                echo "<div id='buttons'>";
                echo "<a href='item_add.php' id='addItem' class='addButton' onclick='return handleClick(event)'></a>";
                echo "</div>";
            }
        ?>
        <script>
        let editButtonShown = false; 
        function handleClick(event) {
            if (!editButtonShown) {
                event.preventDefault(); 

                let container = document.getElementById("buttons");

                let addItemButton = document.getElementById('addItem');

                let editButton = document.createElement("a");
                editButton.id = "editButton";
                editButton.href = "item_add.php?piece=";

                addItemButton.innerText = "Przedmiot";
                addItemButton.style = "border-radius: 10%;";

                editButton.style = "position: fixed; right: 250px; margin: 0;";
                editButton.innerText = "Egzemplarz";
                editButton.classList.add("addButton");

                container.appendChild(editButton);
                editButtonShown = true;
            }
            return editButtonShown; 
        }
        </script>
    </main>
    <footer>
    <section id="footer">
        <p>Stronę stworzył: Kajetan Kufieta</p>
        <a href="https://github.com/Kaiito325" target="_blank"><img src="../images/github.png" alt="github" class="icon"></a>
        <a href="https://linkedIn.com/in/kajetan-kufieta-460a23305" target="_blank"><img src="../images/linkedIn.png" alt="github" class="icon"></a>
    </section>
</footer>
</body>