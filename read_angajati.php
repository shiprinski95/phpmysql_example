<?php
// Verificăm existența parametrului id înainte de a trece mai departe
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Includem fișierul de configurare
    require_once "config.php";
    
    // Pregătim interogarea de selecție
    $sql = "SELECT * FROM Angajati WHERE id = ?";
    
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
                $nume = htmlspecialchars($row["Nume"]);
                $prenume = htmlspecialchars($row["Prenume"]);
                $varsta = htmlspecialchars($row["Varsta"]);
                $nrTel = htmlspecialchars($row["NrTel"]);
                $depart = htmlspecialchars($row["Depart"]);
                $dtAng = htmlspecialchars($row["DtAng"]);
                $salariu = htmlspecialchars($row["Salariu"]);
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
    <title>Vizualizare angajat</title>
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
                    <h1 class="mt-5 mb-3">Vizualizare angajat</h1>
                    <div class="form-group">
                        <label>Nume</label>
                        <p><b><?php echo $row["Nume"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Prenume</label>
                        <p><b><?php echo $row["Prenume"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Vârstă</label>
                        <p><b><?php echo $row["Varsta"]; ?> ani</b></p>
                    </div>
                    <div class="form-group">
                        <label>Numărul de telefon</label>
                        <p><b><?php echo $row["NrTel"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Departament</label>
                        <p><b><?php echo $row["Depart"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Data angajării</label>
                        <p><b><?php echo $row["DtAng"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Salariu</label>
                        <p><b><?php echo $row["Salariu"]; ?> MDL</b></p>
                    </div>
                    <p><a href="index_angajati.php" class="btn btn-primary">Înapoi</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>