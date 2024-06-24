<?php
// Verificăm existența parametrului id înainte de a trece mai departe
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Includem fișierul de configurare
    require_once "config.php";
    
    // Pregătim interogarea de selecție
    $sql = "SELECT * FROM Automobile WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Legăm variabilele pregătite la interogare
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Setăm parametrii
        $param_id = trim($_GET["id"]);
        
        // Încercăm să executăm interogarea
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Preluăm rândul rezultat ca un tablou asociativ. De îndată ce setul de
		rezultate conține doar un rând, nu trebuie să folosim bucla while */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
		        // Preluăm valorile individuale a câmpurilor
                // cu protecție contra atacurilor de tip XSS
                $marca = htmlspecialchars($row["Marca"]);
                $model = htmlspecialchars($row["Model"]);
                $rulaj = htmlspecialchars($row["Rulaj"]);
                $tipCaros = htmlspecialchars($row["TipCaros"]);
                $tipComb = htmlspecialchars($row["TipComb"]);
                $putere = htmlspecialchars($row["Putere"]);
                $capMot = htmlspecialchars($row["CapMot"]);
                $cutVit = htmlspecialchars($row["CutVit"]);
                $descr = htmlspecialchars($row["Descr"]);
            } else{
                // URL-ul nu conține un parametru id valid. Redirecționare spre pagina de eroare
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Ceva a mers greșit. Vă rugăm să încercați din nou mai târziu.";
        }
    }
     
    // Închidem interogarea
    mysqli_stmt_close($stmt);
    
    // Închidem conecțiunea
    mysqli_close($link);
} else{
    // URL-ul nu conține parametrul id. Redirecționare spre pagina de eroare
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Vizualizare automobil</title>
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
                    <h1 class="mt-5 mb-3">Vizualizare automobil</h1>
                    <div class="form-group">
                        <label>Marca</label>
                        <p><b><?php echo $row["Marca"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Modelul</label>
                        <p><b><?php echo $row["Model"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Rulajul</label>
                        <p><b><?php echo $row["Rulaj"]; ?> km</b></p>
                    </div>
                    <div class="form-group">
                        <label>Tipul caroseriei</label>
                        <p><b><?php echo $row["TipCaros"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Tipul combustibilului</label>
                        <p><b><?php echo $row["TipComb"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Puterea</label>
                        <p><b><?php echo $row["Putere"]; ?> CP</b></p>
                    </div>
                    <div class="form-group">
                        <label>Capacitatea motorului</label>
                        <p><b><?php echo $row["CapMot"]; ?> L</b></p>
                    </div>
                    <div class="form-group">
                        <label>Cutia de viteze</label>
                        <p><b><?php echo $row["CutVit"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Descriere</label>
                        <p><b><?php echo $row["Descr"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Înapoi</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>