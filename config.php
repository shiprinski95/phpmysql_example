<?php
/* Parametrii pentru conectarea la baza de date. Serverul MySQL
by default folosește username-ul 'root' fără parolă */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'autosalon');
// Am creat constante cu ajutorul funcţiei define

// Încercarea de a se conecta la baza de date MySQL
// Am declarat variabila globală link
global $link;
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Verificarea conexiunii (operatorul de comparație identic)
if($link === false)
    die("EROARE: Conexiunea la baza de date a eșuat: " . mysqli_connect_error());

// Stabilim charset-ul pentru utilizarea simbolurilor Unicode (diacritice)
mysqli_set_charset($link,"utf8");

// Crearea BD dacă aceasta nu există
$sql = "CREATE DATABASE IF NOT EXISTS ".DB_NAME;

// Stabilirea conexiunii la BD
if($link->query($sql) === false)
    echo "Eroare la crearea bazei de date: " . $link->error;

// Selectarea bazei de date necesare
$link->select_db(DB_NAME);

// Crearea tabelului Angajați
$sql = "CREATE TABLE IF NOT EXISTS Angajati (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Nume VARCHAR(30) NOT NULL,
    Prenume VARCHAR(30) NOT NULL,
    Varsta INT(2) UNSIGNED NOT NULL,
    NrTel VARCHAR(13) NOT NULL,
    Depart VARCHAR(20) NOT NULL,
    DtAng DATE NOT NULL,
    Salariu INT(6) UNSIGNED NOT NULL
)";

// Verificăm dacă interogarea a avut loc cu succes
if($link->query($sql) === FALSE)
    echo "Eroare la crearea tabelului Angajați: " . $link->error;

// Crearea tabelului Filiale
$sql = "CREATE TABLE IF NOT EXISTS Filiale (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Adresa VARCHAR(80) NOT NULL,
    Sector VARCHAR(20) NOT NULL,
    NrTel VARCHAR(13) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    NrAng INT(3) UNSIGNED NOT NULL,
    DtInf DATE NOT NULL,
    Serv VARCHAR(250) NOT NULL
)";

// Verificăm dacă interogarea a avut loc cu succes
if($link->query($sql) === FALSE)
    echo "Eroare la crearea tabelului Filiale: " . $link->error;

// Crearea tabelului Automobile
$sql = "CREATE TABLE IF NOT EXISTS Automobile (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Marca VARCHAR(50) NOT NULL,
    Model VARCHAR(50) NOT NULL,
    Rulaj INT(7) UNSIGNED NOT NULL,
    TipCaros VARCHAR(20) NOT NULL,
    TipComb VARCHAR(20) NOT NULL,
    Putere INT(3) UNSIGNED NOT NULL,
    CapMot FLOAT(2,1) NOT NULL,
    CutVit VARCHAR(20) NOT NULL,
    Descr TEXT NOT NULL
)";

// Verificăm dacă interogarea a avut loc cu succes
if($link->query($sql) === FALSE)
    echo "Eroare la crearea tabelului Automobile: " . $link->error;

// Crearea tabelului Users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

// Verificăm dacă interogarea a avut loc cu succes
if($link->query($sql) === FALSE)
    echo "Eroare la crearea tabelului Users: " . $link->error;
?>