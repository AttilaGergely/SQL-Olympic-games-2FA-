<?php
require_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $fullname = $_SESSION['fullname'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    echo "Do databázy si pridal nového športovca!";

} 
elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $email = $_SESSION['email'];
    $fullname = $_SESSION['name'];
    echo "Do databázy si pridal nového športovca!";
}


try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (!isset($_GET['search_term'])) {
        $search_term = '';
    } else {
        $search_term = $_GET['search_term'];
    }
    $query = "SELECT person.id,  person.name, person.surname, game.year, game.city, game.type, placement.discipline,
              placement.placing FROM person JOIN placement ON person.id = placement.person_id 
              JOIN game ON game.id = placement.games_id 
              WHERE placement.placing = 1 AND (person.name LIKE '%$search_term%' OR person.surname LIKE '%$search_term%' OR game.year LIKE
              '%$search_term%')
              ORDER BY person.name ";
    



    $stmt = $db->query($query); 
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query_top10 = "SELECT person.name,person.id, person.surname, COUNT(*) as total_gold 
                    FROM person JOIN placement ON person.id = placement.person_id 
                    WHERE placement.placing = 1 
                    GROUP BY person.id 
                    ORDER BY total_gold DESC 
                    LIMIT 10";
    $stmt_top10 = $db->query($query_top10);
    $top10_results = $stmt_top10->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="style.css">
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
    <br>
    <h1><a href="index.php" class="invis">Zadanie 1</a></h1>

    <form method="get" action="index.php" >
        
        <h2>Prvé miesta</h2>
        <style>
            label {
  display: inline-block;
  margin-right: 10px;
  color: #444;
  font-size: 16px;
  font-weight: bold;
}

input[type="text"] {
  padding: 5px;
  outline: solid black 1px;
  background-color: #eee;
  color: #333;
  font-size: 16px;
}

input[type="submit"] {
  background-color: #333;
  color: #fff;
  border: none;
  padding: 8px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #444;
}

        </style>
        <label for="search_term">Hľadaj:</label>
        <input type="text" id="search_term" name="search_term">
        <input class="navbar-brand" type="submit" value="Search">
    </form>
    


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
            <style>
                a{
                    text-decoration:none;
                    color:black;
                }
            </style>
        <?php
        foreach($results as $result){
            echo "<tr><td><a href='sportovci.php?id=" . $result["id"] . "'>" . $result["name"] . "</a></td><td>" . $result["surname"] . "</td><td>" . $result["year"] . "</td><td>" . $result["city"] . "</td><td>" . $result["type"] . "</td><td>"
          . $result["discipline"] . "</td></tr>";
            } 
            ?>
        </table>


    <table id="table2" class="table">
        <thead>
        <h2>Top 10</h2>
        <tr>
            <th onclick="sortTable(0, 'table2')"><b>Meno</b></th>
            <th onclick="sortTable(1, 'table2')"><b>Priezvisko</b></th>
            <th onclick="sortTable(2, 'table2')"><b>Počet medailí</b></th>
        
        </tr>
        </thead>
        <tbody>
        <?php
        
        foreach($top10_results as $top10){
            echo "<tr><td><a href='sportovci.php?id=" . $top10["id"] . "'>" . $top10["name"] . "</a></td><td>" .$top10["surname"] ."</td><td>" . $top10["total_gold"] .
                 "</tr></td>" ;
            } 
            ?>
        </tbody>
        </div>
        </body>
        </html>
