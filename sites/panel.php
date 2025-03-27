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
            <li><input type="text" name="search" id="search" placeholder="🔍    szukaj"></li>
        </ul>
    </menu>
    <main>
        <?php
        if(true){
            echo "<section id='loginSection'>";
                echo "<h1>Zaloguj się</h1>";
                echo "<form action='' method='post' id='loginForm'>";
                echo "<label for='login'>login: </label>";
                echo "<input type='text' id='Login' name='login'>";
                echo "<label for='password'>Hasło: </label>";
                echo "<input type='password' id='password' name='password'>";
                echo "<input type='submit' value='Zaloguj'>";
                echo "Nie masz konta? <a href='panel.php?singup=true'>Zarejestruj</a>'";
                echo "</form>";
            echo "</section>";
        }
        ?>
        <?php
            session_start();
            $db = mysqli_connect('localhost', 'root', '', 'magazyn');

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $_POST['login'];
                $_POST['password'];


            }
        ?>
    </main>
</body>