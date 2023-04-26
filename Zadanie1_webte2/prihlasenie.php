<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'vendor/autoload.php';
require_once 'config.php';

// Inicializacia Google API klienta
$client = new Google\Client();

// Definica konfiguracneho JSON suboru pre autentifikaciu klienta.
// Subor sa stiahne z Google Cloud Console v zalozke Credentials.
$client->setAuthConfig('../../client_secret.json');

// Nastavenie URI, na ktoru Google server presmeruje poziadavku po uspesnej autentifikacii.
$redirect_uri = "https://site92.webte.fei.stuba.sk/oh/redirect.php";
$client->setRedirectUri($redirect_uri);

// Definovanie Scopes - rozsah dat, ktore pozadujeme od pouzivatela z jeho Google uctu.
$client->addScope("email");
$client->addScope("profile");

// Vytvorenie URL pre autentifikaciu na Google server - odkaz na Google prihlasenie.
$auth_url = $client->createAuthUrl();

?>

<!doctype html>
<html lang="sk">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OAuth2 cez Google</title>

    <style>
        html {
            max-width: 70ch;
            margin: auto;
            line-height: 1.75;
            font-size: 1.25em;
        }

        p,ul,ol {
            margin-bottom: 2em;
            color: #1d1d1d;
            font-family: sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<style>
    nav {
  background-color: #333;
  height: 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
}

li {
  margin-right: 20px;
}

.navigacia {
  color: white;
  text-decoration: none;
  font-size: 18px;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 5px;
  transition: background-color 0.3s ease-in-out;
}

.navigacia:hover {
  background-color: #555;
}

</style>

<nav>
  <ul>
    <li><a href="index.php" class="navigacia">Tabuľky</a></li>
    <li><a href="prihlasenie.php" class="navigacia">Prihlásenie</a></li>
    <li><a href="register.php" class="navigacia">Registrácia</a></li>
  </ul>
</nav>
<body>
        <hgroup>
            <h1>OAuth2 cez Google</h1>
            <h2>Implementacia pomocou kniznice Google API for PHP</h2>
        </hgroup>

        <?php
        // Ak som prihlaseny, existuje session premenna.
        
            if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
            echo '<h3>Vitaj ' . $_SESSION['name'] . '</h3>';
            echo '<p>Si prihlaseny ako: ' . $_SESSION['email'] . '</p>';
            echo '<p><a role="button" href="restricted.php">Zabezpecena stranka</a>';
            echo '<a role="button" class="secondary" href="logout.php">Odhlas ma</a></p>';
            }
            elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                echo '<h3>Vitaj ' . $_SESSION['fullname'] . '</h3>';
                echo '<p>Si prihlaseny ako: ' . $_SESSION['email'] . '</p>';
                echo '<p><a role="button" href="restricted.php">Zabezpecena stranka</a>';
                echo '<a role="button" class="secondary" href="logout.php">Odhlas ma</a></p>';
            

        } else {
            // Ak nie som prihlaseny, zobraz mi tlacidlo na prihlasenie.
            echo '<h3>Nie si prihlaseny</h3>';
            echo '<a role="button" href="' . filter_var($auth_url, FILTER_SANITIZE_URL) . '">Google prihlasenie</a><br><br>';
            echo '<a role="button" href="login.php">Vlastné prihlásenie</a>';
            
        }
        ?>


</body>
</html>