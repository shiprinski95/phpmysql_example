<?php
   session_start();
   // Includem fișierul de configurare
   require_once "config.php";
   
   // Extragem datele de logare din request-ul POST
   $username =$_POST['username'];
   $passwd =$_POST['parola'];

// Pregătim interogarea pentru selectarea utilizatorului
$sql = "SELECT password FROM users WHERE username = ?";

if($stmt = mysqli_prepare($link, $sql)){
    // Legăm variabilele la query-ul pregătit ca parametri
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    
    // Setăm parametrii, cu protecție contra atacurilor de tip XSS
    $param_username = htmlspecialchars($username);

    // Încercăm să executăm interogarea pregătită
    if(mysqli_stmt_execute($stmt)){
        // Stocăm rezultatul
        mysqli_stmt_store_result($stmt);

        // Verificăm dacă utilizatorul există, apoi legăm rezultatul la o variabilă
        if(mysqli_stmt_num_rows($stmt) == 1){
            mysqli_stmt_bind_result($stmt, $hashed_password);
            if(mysqli_stmt_fetch($stmt)){
                if(password_verify($passwd, $hashed_password)){
                    // Parola este corectă, pornim sesiunea
                    session_regenerate_id();
                    $_SESSION['SESS_LOG'] = "1";
                    session_write_close();
                    header("location: index.php");
                    exit();
                } else {
                    // Parola nu este corectă
                    header("location: logare.php");
                    exit();
                }
            }
        } else {
            // Username-ul nu există
            header("location: logare.php");
            exit();
        }
    } else {
        echo "Oops! A avut loc o eroare. Încercați din nou mai târziu.";
    }
}

// Închidem interogarea
mysqli_stmt_close($stmt);

// Închidem conexiunea
mysqli_close($link);

?>