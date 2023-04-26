<?php
require_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id=$_GET["id"];

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT person.id, person.name, person.surname, game.year, game.city, game.type, placement.discipline, placement.placing 
                FROM person JOIN placement ON person.id = placement.person_id 
                JOIN game ON game.id = placement.games_id WHERE person.id = $id";
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<script src="scripts.js"></script>
<style>
    nav {
  background-color: #333;
  height: 50px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 100px;
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
    
<div class="container-md">
    <h1>Zadanie 1</h1>
    <h2>Detaily o šporotvcovi</h2>
    <table id="table1" class="table">
        <thead>
        <tr>
            <th onclick="sortTable(0, 'table1')"><b>Meno</b></th>
            <th onclick="sortTable(1, 'table1')"><b>Priezvisko</b></th>
            <th onclick="sortTable(2, 'table1')"><b>Rok</b></th>
            <th onclick="sortTable(3, 'table1')"><b>Mesto</b></th>
            <th onclick="sortTable(4, 'table1')"><b>Typ</b></th>
            <th onclick="sortTable(5, 'table1')"><b>Disciplina</b></th>

        </tr>
        </thead>
        <tbody>
        <?php
        foreach($results as $result){
            echo "<tr><td>". $result["name"] . "</td><td>" . $result["surname"] . "</td><td>" . $result["year"] . "</td><td>" . $result["city"] . "</td><td>" . $result["type"] . "</td><td>"
          . $result["discipline"] . "</td></tr>";
            } 
            ?>

        

        </tbody>
        </table>
        <a class="btn btn-danger" href="index.php" role="button">Späť na hlavnú stránku</a>
        </div>
        
        </body>
        </html>
