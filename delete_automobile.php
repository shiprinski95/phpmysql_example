<?php
// Procedăm la operația de ștergere după confirmare
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Includem fișierul de configurare
    require_once "config.php";
    
    // Pregătim interogarea pentru ștergere (prepared statement cu placeholdere - contra atacului de tip SQL Injection)
    // ca alternativa ecranarii cu mysqli_real_escape_string()
    $sql = "DELETE FROM Automobile WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Legăm variabilele pregătite ca parametri la interogare
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Setăm parametrii
        $param_id = trim($_POST["id"]);
        
        // Încercăm să executăm interogarea pregătită
        if(mysqli_stmt_execute($stmt)){
            // Ștergerea cu succes a înregistrărilor. Redirecționarea la pagina inițială
            header("location: index.php");
            exit();
        } else{
            echo "Oops! A avut loc o eroare. Încercați din nou mai târziu.";
        }
    }
     
    // Închidem interogarea
    mysqli_stmt_close($stmt);
    
    // Închidem conecțiunea
    mysqli_close($link);
} else{
    // Verificăm existența parameterului id
    if(empty(trim($_GET["id"]))){
        // URL-ul nu conține parameterul id. Redirecționare spre pagina de eroare
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Ștergerea automobilului</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Ștergerea automobilului</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Sunteți încrezut că vreți să ștergeți înregistrarea acestui automobil?</p>
                            <p>
                                <input type="submit" value="Da" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary">Nu</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>