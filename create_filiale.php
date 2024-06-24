<?php
// Includem fișierul de configurare
require_once "config.php";
 
// Definim variabilele și le inițializăm cu valori goale (nule)
$adresa = $sector = $nrTel = $email = $dtInf = $serv = "";
$nrAng = 0;
$adresa_err = $sector_err = $nrTel_err = $email_err = $nrAng_err = $dtInf_err = $serv_err = "";
 
// Procesarea datelor formularului la Submit
// Stabilim metoda de request ca POST
// Am utilizat htmlspecialchars() contra atac de tip XSS
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validăm adresa filialei
    // Prin trim scotem spațiile albe
    $input_adresa = isset($_POST['adresa']) ? trim($_POST["adresa"]) : "";
    // Dacă nu am introdus nimic
    if(empty($input_adresa)){
        $adresa_err = "Introduceți vă rog o adresă.";
    // Validarea prin expresii regulate (doar litere și/sau cifre)
    } elseif(!filter_var($input_adresa, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9ăîâșț.\s]+$/")))){
        $adresa_err = "Introduceți vă rog o adresă validă.";
    // Dacă valoarea este validă
    } else{
        $adresa = htmlspecialchars($input_adresa);
    }

    // Validăm sectorul filialei
    // Prin trim scotem spațiile albe (din față și spate)
    $input_sector = trim($_POST["sector"]);
    // Dacă nu am introdus nimic
    if(empty($input_sector)){
        $sector_err = "Introduceți vă rog un sector.";
    // Validarea prin expresii regulate (doar litere și/sau cifre)
    } elseif(!filter_var($input_sector, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9ăîâșț\s]+$/")))){
        $sector_err = "Introduceți vă rog un sector valid.";
    // Dacă valoarea este validă
    } else{
        $sector = htmlspecialchars($input_sector);
    }

    // Validăm numărul de telefon al filialei
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

    // Verificăm dacă cheia 'email' există în formular
    $input_email = isset($_POST['email']) ? trim($_POST["email"]) : "";
    // Dacă nu am introdus nimic
    if(empty($input_email)){
        $email_err = "Introduceți vă rog adresa poștei electronice.";   
    // Validarea prin expresii regulate speciale
    } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Introduceți vă rog un email valid.";
    // Dacă valoarea este validă
    } else{
        $email = htmlspecialchars($input_email);
    }

    // Validăm numărul angajaților filialei
    // Prin trim scotem spațiile albe (din față și spate)
    // Verificăm dacă cheia 'nrAng' există în formular
    $input_nrAng = isset($_REQUEST['nrAng']) ? trim($_POST["nrAng"]) : "";
    // Dacă nu am introdus nimic
    if(empty($input_nrAng)){
        $nrAng_err = "Introduceți vă rog un număr de angajați.";   
    // Validarea prin ctype_digit (doar numere întregi pozitive)  
    } elseif(!ctype_digit($input_nrAng)){
        $nrAng_err = "Introduceți vă rog un număr de angajați valid.";
    // Dacă numărul de angajați e mai mare decât 250 de persoane
    } elseif($input_nrAng > 250){
        $rulaj_err = "Numărul de angajați nu poate depăși 250 de persoane pentru un SRL.";
    // Dacă valoarea este validă
    } else{
        $nrAng = htmlspecialchars($input_nrAng);
    }

    // Validăm data înființării filialei
    // Prin trim scotem spațiile albe (din față și spate)
    // Verificăm dacă cheia 'dtInf' există în formular
    $input_dtInf = isset($_POST['dtInf']) ? trim($_POST["dtInf"]) : "";
    // Dacă nu am introdus nimic
    if(empty($input_dtInf)){
        $dtInf_err = "Introduceți vă rog data inființării.";   
    // Validarea prin strtotime (dacă poate fi convertit într-o dată validă)  
    } elseif(strtotime($input_dtInf) === false) {
        $dtInf_err = "Introduceți vă rog o dată validă.";
    // Dacă valoarea este validă
    } else{
        $dtInf = htmlspecialchars($input_dtInf);
    }

    // Validăm lista serviciilor oferite de filială
    // Verificăm dacă cheia a fost trimisă în formular
    $serv1 = isset($_REQUEST['serviciu1']) ? $_REQUEST['serviciu1'] : "";
    $serv2 = isset($_REQUEST['serviciu2']) ? $_REQUEST['serviciu2'] : "";
    $serv3 = isset($_REQUEST['serviciu3']) ? $_REQUEST['serviciu3'] : "";
    $serv4 = isset($_REQUEST['serviciu4']) ? $_REQUEST['serviciu4'] : "";
    
    // Construim un array cu serviciile selectate
    $serv_array = array_filter([$serv1, $serv2, $serv3, $serv4]);
    
    // Transformăm array-ul într-un șir de caractere
    $input_serv = implode("; ", $serv_array);

    // Dacă nu am selectat nimic
    if(empty($input_serv)){
        $serv_err = "Selectați vă rog măcar un serviciu.";    
    // Dacă valoarea este validă
    } else{
        $serv = $input_serv;
    }
    
    // Verificăm errorile input-urilor înainte de insertare în baza de date
    if(empty($adresa_err) && empty($sector_err) && empty($nrTel_err) && empty($email_err) && empty($nrAng_err) && empty($dtInf_err) && empty($serv_err)){
        // Pregătim query-ul de inserție (prepared statement cu placeholdere - contra atacului de tip SQL Injection)
        // ca alternativa ecranarii cu mysqli_real_escape_string()
        $sql = "INSERT INTO Filiale (Adresa, Sector, NrTel, Email, NrAng, DtInf, Serv) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Legăm variabilele la query-ul pregătit ca parametri
            mysqli_stmt_bind_param($stmt, "ssssiss", $param_adresa, $param_sector, $param_nrTel, $param_email, $param_nrAng, $param_dtInf, $param_serv);
            
            // Setăm parametrii
            $param_adresa = $adresa;
            $param_sector = $sector;
            $param_nrTel = $nrTel;
            $param_email = $email;
            $param_nrAng = $nrAng;
            $param_dtInf = $dtInf;
            $param_serv = $serv;
            
            // Încercăm să executăm interogarea pregătită
            if(mysqli_stmt_execute($stmt)){
                // Înregistrările create cu succes, redirecționare spre pagina inițială 
                header("location: index_filiale.php");
                exit();
            } else{
                echo "Oops! A avut loc o eroare. Încercați din nou mai târziu.";
            }
        }
         
        // Închidem interogarea
        mysqli_stmt_close($stmt);
    }
}
?>
 
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Adăugare filială</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }

	/* Stilizare input radio */
        input[type="radio"] {
            /* Setare dimensiuni */
            width: 20px; /* Lățime */
            height: 20px; /* Înălțime */
            /* Centrare */
            display: inline-block;
            vertical-align: middle;
        }

        /* Stilizare label pentru input radio */
        input[type="radio"] + label {
              /* Aliniere la dreapta */
              text-align: right;
              /* Spațiere */
              margin-right: 15px;
        }

        /* Stilizare label pentru input radio (poziționare după input) */
        input[type="radio"] + label:before {
             content: "";
             display: inline-block;
             width: 20px; /* Lățime */
             height: 20px; /* Înălțime */
             margin-right: 5px;
             vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Adăugare filială</h2>
                    <p>Completați câmpurile formularului pentru a adăuga filiala la baza de date.</p>
                    <!-- Am utilizat functia de filtrare htmlspecialchars() contra atacurilor de tip XSS (Cross-Site Scripting) -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Adresa</label>
                            <input type="text" name="adresa" placeholder="Introduceți adresa aici" class="form-control <?php echo (!empty($adresa_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($adresa); ?>">
                            <span class="invalid-feedback"><?php echo $adresa_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Sectorul</label><br>
  			    <input type="radio" id="buiucani" name="sector" checked="checked" value="Buiucani" class="form-control <?php echo (!empty($sector_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sector); ?>">
  			    <label for="buiucani">Buiucani</label><br>
                            <input type="radio" id="ciocana" name="sector" value="Ciocana" class="form-control <?php echo (!empty($sector_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sector); ?>">
                            <label for="ciocana">Ciocana</label><br>
                            <input type="radio" id="rascani" name="sector" value="Râșcani" class="form-control <?php echo (!empty($sector_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sector); ?>">
                            <label for="rascani">Râșcani</label><br>
                            <input type="radio" id="botanica" name="sector" value="Botanica" class="form-control <?php echo (!empty($sector_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sector); ?>">
                            <label for="botanica">Botanica</label><br>
                            <input type="radio" id="centru" name="sector" value="Centru" class="form-control <?php echo (!empty($sector_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($sector); ?>">
                            <label for="centru">Centru</label><br>
                            <span class="invalid-feedback"><?php echo $sector_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Numărul de telefon</label>
                            <input type="tel" name="nrTel" placeholder="Introduceți numărul de telefon" class="form-control <?php echo (!empty($nrTel_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nrTel); ?>">
                            <span class="invalid-feedback"><?php echo $nrTel_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Poșta electronică</label>
                            <input type="email" name="email" placeholder="Introduceți poșta electronică" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Numărul de angajați</label>
                            <input type="number" name="nrAng" min="1" max="250" value="1" class="form-control <?php echo (!empty($nrAng_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($nrAng); ?>">
                            <span class="invalid-feedback"><?php echo $nrAng_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Data inființării</label>
                            <input type="date" name="dtInf" class="form-control <?php echo (!empty($dtInf_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($dtInf); ?>">
                            <span class="invalid-feedback"><?php echo $dtInf_err;?></span>
                        </div>


                        <div class="form-group">
                            <label>Lista serviciilor oferite</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="serviciu1" value="Vânzare mașini noi">
                                <label class="form-check-label">Vânzare mașini noi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="serviciu2" value="Vânzare mașini second-hand">
                                <label class="form-check-label">Vânzare mașini second-hand</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="serviciu3" value="Service auto">
                                <label class="form-check-label">Service auto</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="serviciu4" value="Piese de schimb">
                                <label class="form-check-label">Piese de schimb</label>
                            </div>
                            <span class="invalid-feedback"><?php echo $serv_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Adăugare">
                        <a href="index_filiale.php" class="btn btn-secondary ml-2">Anulare</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>