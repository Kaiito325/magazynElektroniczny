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
            <a href="panel.php">
                <?php
                    echo "<li>Panel</li>";
                ?>
            </a>
            <form action="search.php" method="get">
                <li><input type="text" name="search" id="search" placeholder="🔍    szukaj"></li>
            </form>
            <?php
            session_start();
            include '../functions.php';
            if(isset($_SESSION['login'])){
                echo "<a href='logout.php' id='logout-btn'>";
                echo "    <li>🚪 Wyloguj</li>";
                echo "</a>";
            }
            ?>
        </ul>
    </menu>
    <main>
        <?php
        $db = mysqli_connect('localhost', 'root', '', 'magazyn');
        if(isset($_SESSION['power'])){

            echo "<section id='mainPanel'>";
                echo "<h1>Witaj ". $_SESSION['name'] ."</h1>";
                echo "<p>Zalogowano jako ". $_SESSION['login'] ." ";
                echo "<br><br>";
                if(!checkPowerDifferentThan(2)){
                    echo "<button id='editButton'><a href='users.php'>Użytkownicy</a></button><br>";
                }
                if(!checkPowerDifferentThan(2)){
                        echo "<button id='editButton'><a href='history.php'>Zobacz całą historię</a></button>";
                }
                if(checkPowerDifferentThan(0)){
                    echo "<h1>Twoja historia zmian</h1>";
                    $historySelect = "SELECT dziennik_zmian.id, przedmioty.nazwa, akcja, dziennik_zmian.opis, data_zmiany FROM dziennik_zmian INNER JOIN egzemplarze ON egzemplarze.id = dziennik_zmian.id_egzemplarze INNER JOIN przedmioty ON egzemplarze.id_przedmiotu = przedmioty.id WHERE id_uzytkownika = '" . $_SESSION['id'] . "'";
                    $historyQuery = mysqli_query($db, $historySelect);
                    echo "<table class='normalTable'";
                    echo "<tr> <th>id</th> <th>nazwa przedmiotu</th> <th>akcja</th> <th>opis</th> <th>data zmiany</th> </tr>";
                    while($historyRow = mysqli_fetch_row($historyQuery)){
                        echo "<tr>";
                        echo "<td>$historyRow[0]</td> <td>$historyRow[1]</td> <td>$historyRow[2]</td> <td>$historyRow[3]</td> <td>$historyRow[4]</td> ";
                        echo "</tr>";
                    }
                    
                }
            echo "</section>";
        }
        else if(!isset($_GET['singup'])){
            echo "<section id='loginSection'>";
                echo "<h1>Zaloguj się</h1>";
                echo "<form action='' method='post' id='loginForm'>";
                echo "<label for='login'>login: </label>";
                echo "<input type='text' id='login' name='login' required>";
                echo "<label for='password'>Hasło: </label>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<input type='submit' value='Zaloguj'>";
                echo "Nie masz konta? <a href='panel.php?singup=true'>Zarejestruj</a>'";
                echo "</form>";
            echo "</section>";
        }else{
            echo "<section id='signupSection'>";
                echo "<h1>Zarejestruj się</h1>";
                echo "<form action='' method='post' id='signupForm'>";
                    echo "<label for='login'>login: </label>";
                    echo "<input type='text' id='login' name='login' required>";
                    echo "<label for='password'>Hasło: </label>";
                    echo "<input type='password' id='password' name='password' required>";
                    echo "<label for='name'>Imie: </label>";
                    echo "<input type='text' id='name' name='name' required>";
                    echo "<label for='lastName'>Nazwisko: </label>";
                    echo "<input type='text' id='lastName' name='lastName' required>";
                    echo "<input type='submit' value='Zarejestruj'>";
                    echo "masz konto? <a href='panel.php'>Zaloguj</a>";
                echo "</form>";
            echo "</section>";
        }
        ?>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST['name'])){
                    $login = $_POST['login'];
                    $password = $_POST['password'];
                    $name = $_POST['name'];
                    $lastName = $_POST['lastName'];
                    if(sprawdzHaslo($password) == "dobre"){
                        $loginCheck = "SELECT id FROM uzytkownicy WHERE login = '$login'";
                        $queryLogin = mysqli_query($db, $loginCheck);
                        if(mysqli_num_rows($queryLogin) > 0){
                            echo "<script>alert('Login już istnieje! Wybierz inny.');</script>";
                        }else{
                            $ins = "INSERT INTO uzytkownicy(imie, nazwisko, login, haslo) VALUES ('$name', '$lastName', '$login', '".password_hash($password, PASSWORD_DEFAULT)."')";
                            if(!mysqli_query($db,$ins)){
                                die("Błąd mysqli: " . mysqli_error($db));
                            }else{
                                echo "<script>
                                    alert('Zarejestrowano!');
                                    window.location.href = 'panel.php';
                                </script>";
                            }
                        }
                    }else{
                        echo "<script>alert('".sprawdzHaslo($password)."');</script>";
                    }
                    
                }else if (isset($_POST['login']) && isset($_POST['password'])){
                    $login = $_POST['login'];
                    $password = $_POST['password'];

                    $s = "SELECT haslo FROM uzytkownicy WHERE login = '$login'";
                    $q = mysqli_query($db, $s);
                    if($fRow = mysqli_fetch_row($q)){
                        if(password_verify($password, $fRow[0])){
                            $userSelect = "SELECT imie, nazwisko, login, uprawnienia, id FROM uzytkownicy WHERE login = '$login';";
                            $userQuery = mysqli_query($db, $userSelect);
                            while($userData = mysqli_fetch_row($userQuery)){
                                $_SESSION['name'] = $userData[0];
                                $_SESSION['lastName'] = $userData[1];
                                $_SESSION['login'] = $userData[2];
                                $_SESSION['power'] = $userData[3];
                                $_SESSION['id'] = $userData[4];
                            }
                            echo "<script>
                                    alert('Zalogowano!');
                                    window.location.href = 'panel.php';
                                </script>";
                            exit;
                        }
                    }
                    echo"<h3 style='text-align: center;'>❌ Nie poprawny login bądź hasło</h3>";


                }


            }
            function sprawdzHaslo($haslo) {
                if (strlen($haslo) < 8) {
                    return "❌ Hasło musi mieć co najmniej 8 znaków!";
                }
                if (!preg_match('/[A-Z]/', $haslo)) {
                    return "❌ Hasło musi zawierać przynajmniej jedną dużą literę!";
                }
                if (!preg_match('/[a-z]/', $haslo)) {
                    return "❌ Hasło musi zawierać przynajmniej jedną małą literę!";
                }
                if (!preg_match('/[0-9]/', $haslo)) {
                    return "❌ Hasło musi zawierać przynajmniej jedną cyfrę!";
                }
                if (!preg_match('/[\W_]/', $haslo)) { 
                    return "❌ Hasło musi zawierać przynajmniej jeden znak specjalny!";
                }
                return "dobre";
            }
            mysqli_close($db);
        ?>
    </main>
    <footer>
    <section id="footer">
        <p>Stronę stworzył: Kajetan Kufieta</p>
        <a href="https://github.com/Kaiito325" target="_blank"><img src="../images/github.png" alt="github" class="icon"></a>
        <a href="https://linkedIn.com/in/kajetan-kufieta-460a23305" target="_blank"><img src="../images/linkedIn.png" alt="github" class="icon"></a>
    </section>
</footer>
</body>