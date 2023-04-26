<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php';

// Ak je pouzivatel prihlaseny, ziskam data zo session, pracujem s DB etc...
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $fullname = $_SESSION['fullname'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    

} 
elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $email = $_SESSION['email'];
    $fullname = $_SESSION['name'];
}
else {
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: prihlasenie.php');
}
    // Create connection
    
    

    // Define variables and set to empty values
    $meno = $surname = $birth_day = $birth_place = $birth_country = $death_day = $death_place = $death_country = "";
    $conn = new mysqli($hostname, $username, $password, $dbname);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["pridaj"])) {
            $meno = $_POST["name"];
            $surname = $_POST["surname"];
            $birth_day = $_POST["birth_day"];
            $birth_place =$_POST["birth_place"];
            $birth_country = $_POST["birth_country"];
            $death_day = empty($_POST['death_day']) ? NULL : $_POST['death_day'];
            $death_place = empty($_POST['death_place']) ? NULL : $_POST['death_place'];
            $death_country = empty($_POST['death_country']) ? NULL : $_POST['death_country'];

            $conn = new mysqli($hostname, $username, $password, $dbname);

    
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind statement
                $sql = "INSERT INTO person (name, surname, birth_day, birth_place, birth_country, death_day, death_place, death_country) VALUES ('$meno', '$surname', '$birth_day', '$birth_place', '$birth_country', ";
                $sql .= $death_day ? "'$death_day', " : "NULL, ";
                $sql .= $death_place ? "'$death_place', " : "NULL, ";
                $sql .= $death_country ? "'$death_country')" : "NULL)";
            // Execute statement
            if ($conn->query($sql) === TRUE) {
                echo "Do databázy si pridal nového športovca!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
                }
                else if (isset($_POST["umiestnenie"])) {
                    $person_id = $_POST['person_id'];
                    $game_id = $_POST['game_id'];
                    $placing = $_POST['placing'];
                    $discipline = $_POST['discipline'];
            
            
                    $insert_query = "INSERT INTO placement (person_id, games_id, placing, discipline) 
                                    VALUES ('$person_id', '$game_id', '$placing', '$discipline')";
                    $result = mysqli_query($conn, $insert_query);
            
                    if ($result) {
                        echo "Do databázy si pridal umiestnenie športovca!";
                    } else {
                        echo "Error adding record: " . mysqli_error($conn);
                    }
                    }
    }

    

?>

<!doctype html>
<html lang="sk">
<head>
<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OAuth2 cez Google - Zabezpecena stranka</title>

    <style>
        html {
            max-width: 70ch;
            margin: auto;
            font-size: 1.25em;
        }

        h1,h2,h3,h4,h5,h6 {
            margin: 3em 0 1em;
        }

        p,ul,ol {
            margin-bottom: 2em;
            color: #1d1d1d;
            font-family: sans-serif;
        }

        
    </style>
    
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

        


    <h3>Vitaj <?php echo $fullname ?></h3>
    <p>Si prihlaseny pod emailom: <?php echo $email?></p>
    



    <a role="button" class="secondary" href="logout.php">Odhlasenie</a></p>
    <a role="button" href="prihlasenie.php">Spat na hlavnu stranku</a></p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="name">Meno:</label>
            <input type="text" name="name" required>
            
            <label for="surname">Priezvisko:</label>
            <input type="text" name="surname" required>
            
            <label for="birth_day">Dátum narodenia:</label>
            <input type="date" name="birth_day" required>
            
            <label for="birth_place">Miesto narodenia:</label>
            <input type="text" name="birth_place" required>
            
            <label for="birth_country">Mesto Narodenia:</label>
            <input type="text" name="birth_country" required>
            
            <label for="death_day">Deň smrti:</label>
            <input type="date" name="death_day">
            
            <label for="death_place">Miesto smrti:</label>
            <input type="text" name="death_place">
            
            <label for="death_country">Mesto smrti:</label>
            <input type="text" name="death_country">
            
            <input type="submit" value="Submit" name="pridaj">
</form>

<h2>Pridať umiestnenie</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="person_id">Športovec</label>
    <select name="person_id" id="person_id">
        <option value="">Vyber komu priraďujeme umiestnenie:</option>
        <?php
        $sql = "SELECT id,name,surname FROM person";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['name'] . " " .$row['surname'] . "</option>";
        }
        ?>
        </select>
        <label for="game_id">Olympíjska hra</label>
        <select name="game_id" id="game_id">
        <option value="">Vyber katergóriu:</option>
        <?php
        $query = "SELECT id,type,year,city,country FROM game";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row["id"] . '">' . $row["id"] . "  " . $row['type'] . "  " . $row['year'] . "  " . $row['city'] . "  " . $row['country'] . '</option>';
            }
        }
        ?>
    </select><br>
    <label for="placing">Umiestnenie</label>
    <input type="text" name="placing"><br>
    <label for="discipline">Disciplína</label>
    <input type="text" name="discipline"><br>
    <input type="submit" name="umiestnenie" value="Pridaj umiestnenie">
</form>



</body>
</html>