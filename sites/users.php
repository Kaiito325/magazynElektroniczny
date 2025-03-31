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
            <h1>Użytkownicy</h1>
            <?php
            $db = mysqli_connect('localhost', 'root', '', 'magazyn');
                if(isset($_GET['id'])){
                    $sUser = "SELECT id, imie, nazwisko, login, uprawnienia FROM uzytkownicy WHERE id = " .$_GET['id'] . "";
                    $qUser = mysqli_query($db, $sUser);
                    $row = mysqli_fetch_assoc($qUser);
                    echo "<h1>Edycja danych użytkownika : <i style='color: #666;'> " . $row['imie'] . "</i></h1>";
                    echo "<form action='' method='post' id='editForm'>";
                    echo "<label for='name'>imie</label>";
                    echo "<input type='text' name='name' id='name' value='". $row['imie'] ."'>";
                    echo "<label for='lastName'>Nazwisko</label>";
                    echo "<input type='text' name='lastName' id='lastName' value='". $row['nazwisko'] ."'>";
                    echo "<label for='login'>Login</label>";
                    echo "<input type='text' name='login' id='login' value='". $row['login'] ."'>";
                    echo "<label for='power'>Uprawnienia</label>";
                    echo "<input type='number' name='power' id='power' value='". $row['uprawnienia'] ."' min='0' max='2'>";
                    echo "<input type='submit' value='Edytuj'>";
                    echo "</form>";
                }else{
    
                        $query = "SELECT id, imie, nazwisko, login, uprawnienia FROM uzytkownicy";
                        $result = mysqli_query($db, $query);
                        
                        // Jeśli są wyniki
                        if (mysqli_num_rows($result) > 0) {
                            // Wyświetlanie wyników w tabeli
                            echo "<table class='normalTable'><br>";
                            echo "<tr><th>ID</th><th>Imie</th><th>Nazwisko</th><th>login</th><th>uprawnienia</th><th>Edytuj</th></tr>";
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['imie'] . "</td>";
                                echo "<td>" . $row['nazwisko'] . "</td>";
                                echo "<td>" . $row['login'] . "</td>";
                                echo "<td>" . $row['uprawnienia'] . "</td>";
                                echo "<td><a href='users.php?id=" .$row['id'] . "'>Edytuj</a></td>";
                                echo "</tr>";
                            }
                            
                            echo "</table>";
                        } else {
                            echo "<p>Brak wyników dla zapytania: '$searchTerm'</p>";
                        }
                }
            ?>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $upd = "UPDATE uzytkownicy SET imie = '". $_POST['name'] ."', nazwisko = '". $_POST['lastName'] ."', login = '". $_POST['login'] ."' ,uprawnienia = '". $_POST['power'] ."'  WHERE id=". $row['id'] ."";
                    echo $upd;
                    if (!mysqli_query($db, $upd)) {
                        die("Błąd SQL (egzemplarze): " . mysqli_error($db));
                    }
                    echo "<script>
                                alert('✅ Dane użytkownika zostały edytowane!');
                                window.location.href = '../index.php';
                            </script>";
                        mysqli_close($db);
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