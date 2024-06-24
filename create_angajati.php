<?php
// Includem fișierul de configurare
require_once "config.php";
 
// Definim variabilele și le inițializăm cu valori goale (nule)
// Atribuire multiplă în rând a unei valori mai multor variabile de același tip
$nume = $prenume = $nrTel = $depart = $dtAng = "";
$varsta = $salariu = 0;
$nume_err = $prenume_err = $varsta_err = $nrTel_err = $depart_err = $dtAng_err = $salariu_err = "";
 
// Procesarea datelor formularului la Submit
// Stabilim metoda de request ca POST
// Am utilizat htmlspecialchars() contra atac de tip XSS
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validăm numele angajatului
    // Prin trim scotem spațiile albe
    $input_nume = trim($_POST["nume"]);
    // Dacă nu am introdus nimic
    if(empty($input_nume)){
        $nume_err = "Introduceți vă rog un nume.";
    // Validarea prin expresii regulate (doar litere)
    } elseif(!filter_var($input_nume, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Zăîâșț\s]+$/")))){
        $nume_err = "Introduceți vă rog un nume valid.";
    // Dacă valoarea este validă
    } else{
        $nume = htmlspecialchars($input_nume);
    }

    // Validăm prenumele angajatului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_prenume = trim($_POST["prenume"]);
    // Dacă nu am introdus nimic
    if(empty($input_prenume)){
        $prenume_err = "Introduceți vă rog un prenume.";
    // Validarea prin expresii regulate (doar litere)
    } elseif(!filter_var($input_prenume, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Zăîâșț\s]+$/")))){
        $prenume_err = "Introduceți vă rog un prenume valid.";
    // Dacă valoarea este validă
    } else{
        $prenume = htmlspecialchars($input_prenume);
    }

    // Validăm vârsta angajatului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_varsta = trim($_POST["varsta"]);
    // Dacă nu am introdus nimic
    if(empty($input_varsta)){
        $varsta_err = "Introduceți vă rog o vârstă.";   
    // Validarea prin ctype_digit (doar numere întregi pozitive)  
    } elseif(!ctype_digit($input_varsta)){
        $varsta_err = "Introduceți vă rog o vârstă validă.";
    // Dacă vârsta nu e din intervalul 18-63 (pensionare)
    } elseif($input_varsta <= 18 && $input_varsta >= 63){
        $varsta_err = "Vârsta angajatului trebuie să fie din intervalul 18-63";
    // Dacă valoarea este validă
    } else{
        $varsta = htmlspecialchars($input_varsta);
    }

    // Validăm numărul de telefon al angajatului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_nrTel = trim($_POST["nrTel"]);
    // Dacă nu am introdus nimic
    if(empty($input_nrTel)){
        $nrTel_err = "Introduceți vă rog un număr de telefon.";   
    // Validarea prin expresii regulate (doar cifre)
    } elseif(!filter_var($input_nrTel, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^\d{9,12}$/")))){
        $nrTel_err = "Introduceți vă rog un număr de telefon valid.";
    // Dacă valoarea este validă
    } else{
        $nrTel = htmlspecialchars($input_nrTel);
    }

    // Validăm departamentul angajatului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_depart = trim($_POST["depart"]);
    // Dacă nu am introdus nimic
    if(empty($input_depart)){
        $depart_err = "Introduceți vă rog un departament.";
    // Dacă valoarea este validă
    } else{
        $depart = htmlspecialchars($input_depart);
    }

    // Validăm data angajării angajatului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_dtAng = trim($_POST["dtAng"]);
    // Dacă nu am introdus nimic
    if(empty($input_dtAng)){
        $dtAng_err = "Introduceți vă rog data angajării.";   
    // Validarea prin strtotime (dacă poate fi convertit într-o dată validă)  
    } elseif(strtotime($input_dtAng) === false) {
        $dtAng_err = "Introduceți vă rog o dată validă.";
    // Dacă valoarea este validă
    } else{
        $dtAng = htmlspecialchars($input_dtAng);
    }

    // Validăm salariul angajatului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_salariu = trim($_POST["salariu"]);
    // Dacă nu am introdus nimic
    if(empty($input_salariu)){
        $salariu_err = "Introduceți vă rog un salariu.";   
    // Validarea prin ctype_digit (doar numere întregi pozitive)  
    } elseif(!ctype_digit($input_salariu)){
        $salariu_err = "Introduceți vă rog un salariu valid.";
    // Dacă salariu nu e din intervalul 5000-50000 (salariu minim pe economie)
    } elseif($input_salariu <= 5000 && $input_varsta >= 50000){
        $salariu_err = "Salariu angajatului trebuie să depășească salariu minim pe economie";
    // Dacă valoarea este validă
    } else{
        $salariu = htmlspecialchars($input_salariu);
    }
    
    // Verificăm errorile input-urilor înainte de insertare în baza de date
    if(empty($nume_err) && empty($prenume_err) && empty($varsta_err) && empty($nrTel_err) && empty($depart_err) && empty($dtAng_err) && empty($salariu_err)){
        // Pregătim query-ul de inserție (prepared statement cu placeholdere - contra atacului de tip SQL Injection)
        // ca alternativa ecranarii cu mysqli_real_escape_string()
        $sql = "INSERT INTO Angajati (Nume, Prenume, Varsta, NrTel, Depart, DtAng, Salariu) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Legăm variabilele la query-ul pregătit ca parametri
            mysqli_stmt_bind_param($stmt, "ssisssi", $param_nume, $param_prenume, $param_varsta, $param_nrTel, $param_depart, $param_dtAng, $param_salariu);
            
            // Setăm parametrii
            // cu protecție contra atacurilor de tip XSS
            $param_nume = htmlspecialchars($nume);
            $param_prenume = htmlspecialchars($prenume);
            $param_varsta = htmlspecialchars($varsta);
            $param_nrTel = htmlspecialchars($nrTel);
            $param_depart = htmlspecialchars($depart);
            $param_dtAng = htmlspecialchars($dtAng);
            $param_salariu = htmlspecialchars($salariu);
            
            // Încercăm să executăm interogarea pregătită
            if(mysqli_stmt_execute($stmt)){
                // Înregistrările create cu succes, redirecționare spre pagina inițială 
                header("location: index_angajati.php");
                exit();
            } else{
                echo "Oops! A avut loc o eroare. Încercați din nou mai târziu.";
            }
        }
         
        // Închidem interogarea
        mysqli_stmt_close($stmt);
    }
    
    // Închidem conexiunea
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Adăugare angajat</title>
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
                    <h2 class="mt-5">Adăugare angajat</h2>
                    <p>Completați câmpurile formularului pentru a adăuga angajatul la baza de date.</p>
                    <!-- Am utilizat functia de filtrare htmlspecialchars() contra atacurilor de tip XSS (Cross-Site Scripting) -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nume</label>
                            <input type="text" name="nume" placeholder="Introduceți numele aici" class="form-control <?php echo (!empty($nume_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nume); ?>">
                            <span class="invalid-feedback"><?php echo $nume_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Prenume</label>
                            <input type="text" name="prenume" placeholder="Introduceți prenumele aici" class="form-control <?php echo (!empty($prenume_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($prenume); ?>">
                            <span class="invalid-feedback"><?php echo $prenume_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Vârsta</label>
                            <input type="number" name="varsta" min="18" max="63" value="18" class="form-control <?php echo (!empty($varsta_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($varsta); ?>">
                            <span class="invalid-feedback"><?php echo $varsta_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Numărul de telefon</label>
                            <input type="tel" name="nrTel" placeholder="Introduceți numărul de telefon aici" class="form-control <?php echo (!empty($nrTel_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nrTel); ?>">
                            <span class="invalid-feedback"><?php echo $nrTel_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Departament</label>
                            <select name="depart" class="form-control <?php echo (!empty($depart_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($depart); ?>">
                                <option value="Vânzări" <?php echo ($depart == "Vânzări") ? "selected" : ""; ?>>Vânzări</option>
                                <option value="Service" <?php echo ($depart == "Service") ? "selected" : ""; ?>>Service</option>
                                <option value="Administrativ" <?php echo ($depart == "Administrativ") ? "selected" : ""; ?>>Administrativ</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $depart_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Data angajării</label>
                            <input type="date" name="dtAng" class="form-control <?php echo (!empty($dtAng_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($dtAng); ?>">
                            <span class="invalid-feedback"><?php echo $dtAng_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salariu</label>
                            <input type="number" name="salariu" min="5000" max="50000" value="5000" class="form-control <?php echo (!empty($salariu_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($salariu); ?>">
                            <span class="invalid-feedback"><?php echo $salariu_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Adăugare">
                        <a href="index_angajati.php" class="btn btn-secondary ml-2">Anulare</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>