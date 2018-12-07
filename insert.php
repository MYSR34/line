<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $dbinfo = parse_url(getenv('DATABASE_URL'));
    $dsn =  'pgsql:host=' . $dbinfo['host'] . ';dbname=' . substr($dbinfo['path'], 1);;
    
    try{
        $pdo = new PDO($dsn, $dbinfo['user'], $dbinfo['pass']);
        $sql = sprintf("INSERT INTO events(name, place, startDay, endDay, genre, price, imageURI, siteURI, explanation) ".
                        "VALUES('%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s')", 
                        $data['name'], $data['place'], $data['startDay'], $data['endDay'], $data['genre'], $data['price'], $data['imageURI'], $data['siteURI'], $data['explanation']);
        $result = $pdo->query($sql);

    }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }

}

?>