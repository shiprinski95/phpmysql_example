<?php
// Includem fișierul de configurare
require_once "config.php";

// Definim variabilele și le inițializăm cu valori goale (nule)
$marca = $model = $tipCaros = $tipComb = $cutVit = $descr = "";
$rulaj = $putere = $capMot = 0;
$marca_err = $model_err = $rulaj_err = $tipCaros_err = $tipComb_err = $putere_err = $capMot_err = $cutVit_err = $descr_err = "";

// Verificăm dacă formularul a fost trimis
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesarea datelor formularului la Submit
    // Verificăm dacă id-ul a fost trimis prin POST și nu este gol
    if(isset($_POST["id"]) && !empty($_POST["id"])){
        // Obținem valoarea de intrare ascunsă
        $id = $_POST["id"];

    // Validăm marca automobilului
    // Prin trim scotem spațiile albe
    // Am utilizat htmlspecialchars() contra atac de tip XSS
    $input_marca = trim($_POST["marca"]);
    // Dacă nu am introdus nimic
    if(empty($input_marca)){
        $marca_err = "Introduceți vă rog o marcă.";
    // Validarea prin expresii regulate (doar litere și/sau cifre)
    } elseif(!filter_var($input_marca, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9ăîâșț\s]+$/")))){
        $marca_err = "Introduceți vă rog o marcă validă.";
    // Dacă valoarea este validă
    } else{
        $marca = htmlspecialchars($input_marca);
    }

    // Validăm modelul automobilului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_model = trim($_POST["model"]);
    // Dacă nu am introdus nimic
    if(empty($input_model)){
        $model_err = "Introduceți vă rog un model.";
    // Validarea prin expresii regulate (doar litere și/sau cifre)
    } elseif(!filter_var($input_model, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9ăîâșț\s]+$/")))){
        $model_err = "Introduceți vă rog o marcă validă.";
    // Dacă valoarea este validă
    } else{
        $model = htmlspecialchars($input_model);
    }

    // Validăm rulajul automobilului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_rulaj = trim($_POST["rulaj"]);
    // Dacă nu am introdus nimic
    if(empty($input_rulaj)){
        $rulaj_err = "Introduceți vă rog rulajul.";   
    // Validarea prin ctype_digit (doar numere întregi pozitive)
    } elseif(!ctype_digit($input_rulaj)){
        $rulaj_err = "Introduceți vă rog un rulaj valid.";
    // Dacă rulajul este mai mare decât maximul permis de 1 mln de km parcurși
    } elseif($input_rulaj > 1000000){
        $rulaj_err = "Rulajul nu poate depăși 1 mln de km.";
    // Dacă valoarea este validă
    } else{
        $rulaj = htmlspecialchars($input_rulaj);
    }

    // Validăm tipul caroseriei automobilului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_tipCaros = trim($_POST["tipCaros"]);
    // Dacă nu am introdus nimic
    if(empty($input_tipCaros)){
        $tipCaros_err = "Introduceți vă rog tipul caroseriei.";   
    // Validarea prin expresii regulate (doar litere)
    } elseif(!filter_var($input_tipCaros, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Zăîâșț\s]+$/")))){
        $tipCaros_err = "Introduceți vă rog un tip de caroserie valid.";
    // Dacă valoarea este validă
    } else{
        $tipCaros = htmlspecialchars($input_tipCaros);
    }

    // Validăm tipul combustibilului automobilului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_tipComb = trim($_POST["tipComb"]);
    // Dacă nu am introdus nimic
    if(empty($input_tipComb)){
        $tipComb_err = "Introduceți vă rog tipul combustibilului.";   
    // Validarea prin expresii regulate (doar litere)
    } elseif(!filter_var($input_tipComb, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Zăîâșț\s]+$/")))){
        $tipComb_err = "Introduceți vă rog un tip de combustibil valid.";
    // Dacă valoarea este validă
    } else{
        $tipComb = htmlspecialchars($input_tipComb);
    }

    // Validăm puterea în cai (CP) a automobilului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_putere = trim($_POST["putere"]);
    // Dacă nu am introdus nimic
    if(empty($input_putere)){
        $putere_err = "Introduceți vă rog puterea.";   
    // Validarea prin ctype_digit (doar numere întregi pozitive)  
    } elseif(!ctype_digit($input_putere)){
        $putere_err = "Introduceți vă rog o putere validă.";
    // Dacă puterea este mai mare decât minimul permis de 50 de cai (CP)
    } elseif($input_putere < 50){
        $putere_err = "Puterea nu poate fi mai mică de 50 de cai (CP).";
    // Dacă puterea este mai mare decât maximul permis de 1000 de cai (CP)
    } elseif($input_putere > 1000){
        $putere_err = "Puterea nu poate depăși 1000 de cai (CP).";
    // Dacă valoarea este validă
    } else{
        $putere = htmlspecialchars($input_putere);
    }

    // Validăm capacitatea motorului în litri
    // Prin trim scotem spațiile albe (din față și spate)
    $input_capMot = trim($_POST["capMot"]);
    // Dacă nu am introdus nimic
    if(empty($input_capMot)){
        $capMot_err = "Introduceți vă rog capacitatea motorului.";   
    // Validarea prin is_numeric (doar numere reale pozitive)  
    } elseif(!is_numeric($input_capMot) || $input_capMot <= 0){
        $capMot_err = "Introduceți vă rog o capacitate a motorului validă.";
    // Dacă capacitatea este mai mare decât maximul permis de 6.0 litri
    } elseif($input_capMot < 0.8){
        $capMot_err = "Capacitatea motorului nu poate fi mai mică decât 0.8 litri.";
    // Dacă valoarea este validă
    } elseif($input_capMot > 6.0){
        $capMot_err = "Capacitatea motorului nu poate depăși 6.0 litri.";
    // Dacă valoarea este validă
    } else{
        $capMot = htmlspecialchars($input_capMot);
    }

    // Validăm tipul cutiei de viteze
    // Prin trim scotem spațiile albe (din față și spate)
    $input_cutVit = trim($_POST["cutVit"]);
    // Dacă nu am introdus nimic
    if(empty($input_cutVit)){
        $cutVit_err = "Introduceți vă rog tipul cutiei de viteze.";   
    // Validarea prin expresii regulate (doar litere)
    } elseif(!filter_var($input_cutVit, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Zăîâșț\s]+$/")))){
        $cutVit_err = "Introduceți vă rog un tip de cutie de viteze valid.";
    // Dacă valoarea este validă
    } else{
        $cutVit = htmlspecialchars($input_cutVit);
    }

    // Validăm descrierea automobilului
    // Prin trim scotem spațiile albe (din față și spate)
    $input_descr = trim($_POST["descr"]);
    // Dacă nu am introdus nimic
    if(empty($input_descr)){
        $descr_err = "Introduceți vă rog descrierea automobilului.";   
    // Verificăm dacă descrierea are mai mult de 10 caractere
    } elseif(strlen($input_descr) < 5){
        $descr_err = "Descrierea automobilului trebuie să aibă cel puțin 5 caractere.";
    // Dacă valoarea este validă
    } else{
        $descr = htmlspecialchars($input_descr);
    }

        // Verificăm dacă nu există erori înainte de a actualiza baza de date
        if(empty($marca_err) && empty($model_err) && empty($rulaj_err) && empty($tipCaros_err) && empty($tipComb_err) && empty($putere_err) && empty($capMot_err) && empty($cutVit_err) && empty($descr_err)){
            // Pregătim query-ul de înnoire (prepared statement cu placeholdere - contra atacului de tip SQL Injection)
            // ca alternativa ecranarii cu mysqli_real_escape_string()
            $sql = "UPDATE Automobile SET Marca=?, Model=?, Rulaj=?, TipCaros=?, TipComb=?, Putere=?, CapMot=?, CutVit=?, Descr=? WHERE id=?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Legăm variabilele la query-ul pregătit ca parametri
                mysqli_stmt_bind_param($stmt, "ssissidssi", $param_marca, $param_model, $param_rulaj, $param_tipCaros, $param_tipComb, $param_putere, $param_capMot, $param_cutVit, $param_descr, $param_id);

                // Preluăm valoarile câmpurilor individuale
                // cu protecție contra atacurilor de tip XSS
                $param_marca = htmlspecialchars($marca);
                $param_model = htmlspecialchars($model);
                $param_rulaj = htmlspecialchars($rulaj);
                $param_tipCaros = htmlspecialchars($tipCaros);
                $param_tipComb = htmlspecialchars($tipComb);
                $param_putere = htmlspecialchars($putere);
                $param_capMot = htmlspecialchars($capMot);
                $param_cutVit = htmlspecialchars($cutVit);
                $param_descr = htmlspecialchars($descr);
                $param_id = htmlspecialchars($id);

                // Încercăm să executăm interogarea pregătită
                if(mysqli_stmt_execute($stmt)){
                    // Înregistrările înnoite cu succes, redirecționare spre pagina inițială 
                    header("location: index.php");
                    exit();
                } else{
                    echo "Oops! A avut loc o eroare. Încercați din nou mai târziu.";
                }
            }

            // Închidem interogarea
            mysqli_stmt_close($stmt);
        }
    }
}

// Obținem id-ul automobilului din parametrul URL
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Obținem parameterul URL
    $id =  trim($_GET["id"]);

    // Pregătim interogarea de selecție
    $sql = "SELECT * FROM Automobile WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Legăm variabilele la query-ul pregătit ca parametri
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Setăm parameterii
        $param_id = $id;

        // Încercăm să executăm interogarea pregătită
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Preluăm rândul rezultat ca un tablou asociativ. Dacă setul de
                rezultate conține doar un rând, nu trebuie să folosim bucla while */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Preluăm valorile câmpurilor individuale
                $marca = $row["Marca"];
                $model = $row["Model"];
                $rulaj = $row["Rulaj"];
                $tipCaros = $row["TipCaros"];
                $tipComb = $row["TipComb"];
                $putere = $row["Putere"];
                $capMot = $row["CapMot"];
                $cutVit = $row["CutVit"];
                $descr = $row["Descr"];
            } else{
                // URL-ul nu conține un id valid. Redirecționare spre pagina de eroare
                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Ceva nu a mers bine. Vă rugăm să încercați din nou mai târziu.";
        }
    }

    // Închidem interogarea
    mysqli_stmt_close($stmt);
}  else{
    // URL-ul nu conține parametrul id. Redirecționare spre pagina de eroare
    header("location: error.php");
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Înnoire automobil</title>
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
                    <h2 class="mt-5">Înnoire automobil</h2>
                    <p>Vă rugăm să editați valorile de intrare și să submiteți pentru a actualiza înregistrarea automobilului.</p>
                    <!-- Am utilizat functia de filtrare htmlspecialchars() contra atacurilor de tip XSS (Cross-Site Scripting) -->
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" name="marca" placeholder="Introduceți marca automobilului" class="form-control <?php echo (!empty($marca_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($marca); ?>">
                            <span class="invalid-feedback"><?php echo $marca_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Modelul</label>
                            <input type="text" name="model" placeholder="Introduceți modelul automobilului" class="form-control <?php echo (!empty($model_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($model); ?>">
                            <span class="invalid-feedback"><?php echo $model_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Rulajul</label>
                            <input type="number" name="rulaj" min="1" max="1000000" value="1" class="form-control <?php echo (!empty($rulaj_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($rulaj); ?>">
                            <span class="invalid-feedback"><?php echo $rulaj_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tipul caroseriei</label>
                            <select name="tipCaros" class="form-control <?php echo (!empty($tipCaros_err)) ? 'is-invalid' : ''; ?>">
                                <option value="Sedan" <?php echo ($tipCaros == "Sedan") ? "selected" : ""; ?>>Sedan</option>
                                <option value="Coupe" <?php echo ($tipCaros == "Coupe") ? "selected" : ""; ?>>Coupe</option>
                                <option value="Hatchback" <?php echo ($tipCaros == "Hatchback") ? "selected" : ""; ?>>Hatchback</option>
                                <option value="Crossover" <?php echo ($tipCaros == "Crossover") ? "selected" : ""; ?>>Crossover</option>
                                <option value="Minivan" <?php echo ($tipCaros == "Minivan") ? "selected" : ""; ?>>Minivan</option>
                                <option value="Cabriolet" <?php echo ($tipCaros == "Cabriolet") ? "selected" : ""; ?>>Cabriolet</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $tipCaros_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tipul combustibilului</label>
                            <select name="tipComb" class="form-control <?php echo (!empty($tipComb_err)) ? 'is-invalid' : ''; ?>">
                                <option value="Benzină" <?php echo ($tipComb == "Benzină") ? "selected" : ""; ?>>Benzină</option>
                                <option value="Diesel" <?php echo ($tipComb == "Diesel") ? "selected" : ""; ?>>Diesel</option>
                                <option value="Gaz (GPL)" <?php echo ($tipComb == "Gaz (GPL)") ? "selected" : ""; ?>>Gaz (GPL)</option>
                                <option value="Hybrid" <?php echo ($tipComb == "Hybrid") ? "selected" : ""; ?>>Hybrid</option>
                                <option value="Electric" <?php echo ($tipComb == "Electric") ? "selected" : ""; ?>>Electric</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $tipComb_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Puterea (CP)</label>
                            <input type="number" name="putere" min="50" max="1000" value="50" class="form-control <?php echo (!empty($putere_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($putere); ?>">
                            <span class="invalid-feedback"><?php echo $putere_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Capacitatea motorului (L)</label>
                            <input type="number" name="capMot" min="0.8" max="6.0" step="0.1" class="form-control <?php echo (!empty($capMot_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($capMot); ?>">
                            <span class="invalid-feedback"><?php echo $capMot_err;?></span>
                        </div>
			<div class="form-group">
    		            <label>Cutia de viteze</label><br>
                            <?php
                                // Verifica valoarea lui $cutVit și selectează radio button-ul corespunzător
                                $mecanica_checked = $automata_checked = $robotizata_checked = $variator_checked = 0;
				                if ($cutVit == "Mecanică") {
                                    $mecanica_checked = "checked";
                                } elseif ($cutVit == "Automată") {
                                    $automata_checked = "checked";
                                } elseif ($cutVit == "Robotizată") {
                                    $robotizata_checked = "checked";
                                } elseif ($cutVit == "Variator") {
                                    $variator_checked = "checked";
                                } else {
				                    // Dacă valoarea lui $cutVit nu se potrivește cu nicio opțiune, selectăm implicit "Mecanică"
                                    $mecanica_checked = "checked";
                                }
                            ?>
    			    <input type="radio" id="mecanica" name="cutVit" <?php echo $mecanica_checked; ?> value="Mecanică" class="form-control <?php echo (!empty($cutVit_err)) ? 'is-invalid' : ''; ?>">
                            <label for="mecanica">Mecanică</label><br>
                            <input type="radio" id="automata" name="cutVit" <?php echo $automata_checked; ?> value="Automată" class="form-control <?php echo (!empty($cutVit_err)) ? 'is-invalid' : ''; ?>">
                            <label for="automata">Automată</label><br>
                            <input type="radio" id="robotizata" name="cutVit" <?php echo $robotizata_checked; ?> value="Robotizată" class="form-control <?php echo (!empty($cutVit_err)) ? 'is-invalid' : ''; ?>">
                            <label for="robotizata">Robotizată</label><br>
                            <input type="radio" id="variator" name="cutVit" <?php echo $variator_checked; ?> value="Variator" class="form-control <?php echo (!empty($cutVit_err)) ? 'is-invalid' : ''; ?>">
                            <label for="variator">Variator</label><br>
                            <span class="invalid-feedback"><?php echo $cutVit_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Descrierea</label>
			    <textarea id="descr" name="descr" rows="4" cols="50" placeholder="Introduceți descrierea detaliată aici..." class="form-control <?php echo (!empty($descr_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($descr); ?></textarea>
                            <span class="invalid-feedback"><?php echo $descr_err;?></span>
                        </div>

			<input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Modificare">
                        <a href="index.php" class="btn btn-secondary ml-2">Anulare</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>