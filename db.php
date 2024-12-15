<?php

function getHostUsername() {
    return substr(explode("/", $_SERVER["SCRIPT_NAME"])[1], 1);
}

function getDB() {
    $name = getHostUsername();
    $host = "localhost";
    $user = "nnguyen177";
    $pass = "nnguyen177";
    $dbname = "nnguyen177";

    return new mysqli($host, $user, $pass, $dbname);
}

function initTables() {
    $db = getDB();

    $db->query(
        "CREATE TABLE IF NOT EXISTS User(
            username VARCHAR(100) NOT NULL PRIMARY KEY,
            email TEXT NOT NULL,
            fname TEXT NOT NULL,
            lname TEXT,
            pass VARCHAR(72) NOT NULL,
            perm INT
            )"
    );

    $db->query(
        "CREATE TABLE IF NOT EXISTS Card(
            seller VARCHAR(100) NOT NULL,
            addr TEXT NOT NULL,
            age INT NOT NULL,
            price FLOAT NOT NULL,
            img TEXT NOT NULL,
            beds INT,
            baths FLOAT,
            garage INT,
            areaL INT,
            areaW INT,
            FOREIGN KEY(seller) REFERENCES User(username)
        )"
    );

    $db->query(
        "CREATE TABLE IF NOT EXISTS Wishlist(
            email TEXT NOT NULL,
            addr TEXT NOT NULL
        )"
    );

    $db->close();
}

function hashPass($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPass($password, $email) {
    $db = getDB();
    $sql = "SELECT pass FROM User WHERE email=?";
    $statement = $db->prepare($sql);
    $statement->bind_param("s", $email);
    $statement->execute();
    $intermediate = $statement->get_result();
    $result = $intermediate->fetch_assoc();
    $internalPass = $result["pass"];
    $db->close();

    return password_verify($password, $internalPass);
}

function createUser() {
    $db = getDB();
	$registerName =$_POST["username"];
	$registerEmail = $_POST["email"];
	$registerFirstName = $_POST["firstname"];
	$registerLastName = $_POST["lastname"];
	$registerPass = hashPass($_POST["password"]);
	$permNum = 1;
    $statement = $db->prepare("INSERT INTO User (username, email,fname,lname, pass,perm) VALUES(?, ?, ?, ?, ?, ?)");
    $statement->bind_param("sssssi", $registerName, $registerEmail, $registerFirstName, $registerLastName, $registerPass, $permNum);
    $statement->execute();
	$db->close();
}

function createCard() {
    $db = getDB();
    $seller = 'seller';
    $addr = "2013 Random Street, Random City, Random State";
    $age = 5;
    $price = 350000;
    $img = "property-image.jpg";
    $beds = 3;
    $baths = 2;
    $garage = 1;
    $areaL = 60;
    $areaW = 40;
    $db->query(
        "INSERT INTO Card
        (   
            seller,
            addr,
            age,
            price,
            img,
            beds,
            baths,
            garage,
            areaL,
            areaW
        )
        VALUES
        (
            '$seller',
            '$addr',
            '$age',
            '$price',
            '$img',
            '$beds',
            '$baths',
            '$garage',
            '$areaL',
            '$areaW'
        )"
    );
    $db->close();
}

function getCardByAddr($addr) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Card WHERE addr = ?");
    $stmt->bind_param("s", $addr);
    $stmt->execute();
    $out = $stmt->get_result()->fetch_assoc();
	$db->close();
    return $out;
}

function addToWishlist($addr) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Wishlist VALUES(?, ?)");
    $stmt->bind_param("ss", $_SESSION['user_auth'], $addr);
    $stmt->execute();
    $db->close();
}

function getWishlistByEmail($email) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Wishlist WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $ot = $stmt->get_result();
    $out = $ot->fetch_all();
    $db->close();
    return $out;
}

?>