<?php
include "sesiune.php";

// Definim variabilele de mesaj
$error_message = '';
$success_message = '';

// Verificăm dacă există mesaje de eroare sau de succes și le afișăm corespunzător
// cu escapare împotriva atacurilor de tip XSS
if(isset($_SESSION['error_message'])) {
    $error_message = htmlspecialchars($_SESSION['error_message']);
    // Eliminăm mesajul pentru a nu fi afișat din nou
    unset($_SESSION['error_message']);
}
if(isset($_SESSION['success_message'])) {
    $success_message = htmlspecialchars($_SESSION['success_message']);
    // Eliminăm mesajul pentru a nu fi afișat din nou
    unset($_SESSION['success_message']);
}

// Funcție pentru a citi datele din tabelul Angajati și a le scrie într-un fișier .bak
function export_angajati() {
    // Include fișierul de configurare
    require_once "config.php";

    // Numele fișierului de import
    $filename = 'angajati_backup.bak';

    // Interogare pentru a obține toate datele din tabelul Angajati
    $sql = "SELECT * FROM Angajati";

    // Executăm interogarea
    $result = mysqli_query($link, $sql);

    // Verificăm dacă există date de exportat
    if ($result) {
        // Deschidem fișierul pentru scriere
        $file = fopen($filename, 'w');

        // Verificăm dacă fișierul s-a deschis cu succes
        if ($file) {
            // Parcurgem fiecare rând din rezultatul interogării
            while ($row = mysqli_fetch_assoc($result)) {
                // Scriem datele în fișier
                fwrite($file, implode(',', $row) . "\n");
            }

            // Închidem fișierul
            fclose($file);

            // Mesaj de succes
            $_SESSION['success_message'] = "Datele au fost exportate cu succes în fișierul $filename.";
        } else {
            // Mesaj de eroare în cazul în care fișierul nu s-a putut deschide pentru scriere
            $_SESSION['error_message'] = "Eroare la deschiderea fișierului pentru scriere.";
        }
    } else {
        // Mesaj de eroare în cazul în care interogarea a eșuat
        $_SESSION['error_message'] = "Eroare la exportul datelor.";
    }
}

// Funcție pentru a citi datele din fișierul .bak și a le adăuga în tabela Angajați
function import_angajati() {
    // Include fișierul de configurare
    require_once "config.php";

    // Numele fișierului de import
    $filename = 'angajati_backup.bak';

    // Verificăm dacă fișierul există
    if (file_exists($filename)) {
        // Deschidem fișierul pentru citire
        $file = fopen($filename, 'r');

        // Verificăm dacă fișierul s-a deschis cu succes
        if ($file) {
            // Introducem datele în baza de date (prepared statement cu placeholdere - contra atacului de tip SQL Injection)
            // ca alternativa ecranarii cu mysqli_real_escape_string()
            $sql = "INSERT INTO Angajati (Nume, Prenume, Varsta, NrTel, Depart, DtAng, Salariu) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql);

            // Verificăm dacă pregătirea interogării a avut succes
            if ($stmt) {
                // Parcurgem fiecare linie din fișier
                while (($line = fgets($file)) !== false) {
                    // Explodăm linia în array folosind virgula ca separator
                    $data = explode(',', $line);

                    // Asignăm valorile din array la parametrii interogării pregătite
                    mysqli_stmt_bind_param($sql, 'sssssssss', $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);

                    // Executăm interogarea
                    mysqli_stmt_execute($stmt);
                }

                // Închidem interogarea
                mysqli_stmt_close($stmt);

                // Închidem fișierul
                fclose($file);

                // Afisăm un mesaj de succes
                $_SESSION['success_message'] = "Datele au fost importate cu succes din fișierul '$filename'.";
            } else {
                // Afisăm un mesaj de eroare în cazul în care pregătirea interogării a eșuat
                $_SESSION['error_message'] = "Eroare la pregătirea interogării.";
            }
        } else {
            // Afisăm un mesaj de eroare în cazul în care fișierul nu s-a putut deschide
            $_SESSION['error_message'] = "Eroare la deschiderea fișierului.";
        }
    } else {
        // Afisăm un mesaj de eroare în cazul în care fișierul nu există
        $_SESSION['error_message'] = "Fișierul '$filename' nu există.";
    }
}

// Verificăm ce acțiune trebuie să realizăm
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'export') {
        export_angajati();
    } elseif ($_POST['action'] == 'import') {
        import_angajati();
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Manipulare angajați</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 1000px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
        .user-info {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .logout-btn {
            margin-left: 10px;
        }
        .counter-info {
            position: absolute;
            top: 50px;
            right: 10px;
        }
        .upload-btn, .download-btn {
            margin-top: 10px;
        }
	.add-ang-btn {
            margin-left: 30px;
        }

    	.message-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
 	    /* Culoarea roșie pentru mesajul de eroare */
            background-color: #f8d7da;
            /* Culoarea roșie pentru text */
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            /* Culoarea roșie pentru bordură */
            border: 1px solid #f5c6cb;
            /* Umbra */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            z-index: 9999;
    	}

    	.success-message {
	    /* Culoarea verde pentru mesajul de succes */
            background-color: #d4edda;
 	    /* Culoarea verde pentru text */
            color: #155724;
            /* Culoarea verde pentru bordură */
            border-color: #c3e6cb;
    	}

        .close-button {
            float: right;
            cursor: pointer;
	    /* Culoarea text roșu pentru butonul de închidere */
            color: #721c24;
        }

    	.close-button:hover {
	    /* Culoarea text roșu pentru butonul de închidere la hover */
            color: #721c24;
            text-decoration: none;
        }

	/* Stiluri pentru butoane */
	.btn {
    	    font-size: 14px;
    	    padding: 10px 20px;
    	    border-radius: 5px;
    	    cursor: pointer;
	}

        /* Stiluri pentru butoanele de culoare primară */
        .btn-primary {
            background-color: #007bff; /* culoare de fundal albastră */
            border-color: #007bff; /* culoare bordură albastră */
            color: #fff; /* culoare text alb */
        }

        /* Stiluri pentru butoanele de culoare primară la hover */
        .btn-primary:hover {
            /* Culoarea de fundal albastru-închis la hover */
            background-color: #0056b3;
            /* Culoarea bordură albastru-închis la hover */
            border-color: #0056b3;
        }

        /* Stiluri pentru butoanele de culoare primară la focus */
        .btn-primary:focus {
            /* Umbra la focus */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
        }

        /* Stiluri pentru butoanele de culoare primară la activare */
        .btn-primary:active {
            /* Culoarea de fundal albastru-închis la activare */
            background-color: #0041a3;
            /* Culoarea bordură albastru-închis la activare */
            border-color: #0041a3;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Detalii despre angajați</h2>
                        <div class="user-info">
                            <i class="fa fa-user"></i> admin
                            <a href="logout_btn.php" class="btn btn-danger logout-btn">Log out</a>
                        </div>
			<?php
                            if(!isset($_SESSION['count'])) {
                                $_SESSION['count']=1;
                            } else {
                                $_SESSION['count']++;
                            }
			?>
                        <div class="counter-info">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Pagina a fost vizitată de <?php echo $_SESSION['count']; ?> ori.
    			    <br><br><br><span>Alte file:</span>
                            <button class="btn btn-primary ml-2" onclick="window.location.href='index.php'">Automobile</button>
                            <button class="btn btn-primary ml-2" onclick="window.location.href='index_filiale.php'">Filiale</button>
                        </div>
                    </div>

                    <!-- Afisarea mesajelor de eroare si de succes -->
                    <?php if (!empty($error_message)): ?>
                        <div class="message-container" id="errorMessage">
                            <span class="error-message"><?php echo $error_message; ?></span>
                            <a class="close-button" href="javascript:void(0);" onclick="closeMessage('errorMessage')">&times;</a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($success_message)): ?>
                        <div class="message-container success-message" id="successMessage">
                            <span><?php echo $success_message; ?></span>
                            <a class="close-button" href="javascript:void(0);" onclick="closeMessage('successMessage')">&times;</a>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <button type="submit" class="btn btn-primary upload-btn" name="action" value="import"><i class="fa fa-upload"></i> Încarcă</button>
                        <button type="submit" class="btn btn-primary download-btn" name="action" value="export"><i class="fa fa-download"></i> Descarcă</button>
			<a href="create_angajati.php" class="btn btn-success ml-2 add-ang-btn"><i class="fa fa-plus"></i> Adăugare angajat nou</a>
                    </form>

                    <!-- Funcția JavaScript de ascundere a mesajelor (butonul de închidere) -->
                    <script>
                        function closeMessage(messageId) {
                            var message = document.getElementById(messageId);
                            message.style.display = 'none';
                        }
                    </script>

                    <?php
		    // Include fișierul de configurare
                    require_once "config.php";
                    
                    // Încercarea de executare a interogării de selecție
                    $sql = "SELECT * FROM Angajati";
		    try {
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>ID</th>";
                                        echo "<th>Nume</th>";
                                        echo "<th>Prenume</th>";
                                        echo "<th>Vârstă</th>";
                                        echo "<th>Număr de telefon</th>";
					echo "<th>Departament</th>";
					echo "<th>Data angajării</th>";
					echo "<th>Salariu</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['Nume'] . "</td>";
                                        echo "<td>" . $row['Prenume'] . "</td>";
                                        echo "<td>" . $row['Varsta'] . "</td>";
					echo "<td>" . $row['NrTel'] . "</td>";
					echo "<td>" . $row['Depart'] . "</td>";
					echo "<td>" . $row['DtAng'] . "</td>";
					echo "<td>" . $row['Salariu'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read_angajati.php?id='. $row['id'] .'" class="mr-3" title="Vizualizare înregistrare" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update_angajati.php?id='. $row['id'] .'" class="mr-3" title="Înnoire înregistrare" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete_angajati.php?id='. $row['id'] .'" title="Ștergere înregistrare" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Golim set-ul rezultant
                            mysqli_free_result($result);
                        } else {
                            echo '<div class="alert alert-danger"><em>Nu a fost găsită nici o înregistrare.</em></div>';
			}
                        } else {
                            throw new Exception("Interogarea SQL a eșuat.");
                        }
		    } catch (mysqli_sql_exception $e) {
                        // Afișăm mesajul corespunzător în cazul în care tabela nu există
                        echo '<div class="alert alert-danger"><em>Erroare fatală: Tabela "Angajati" nu există în baza de date.</em></div>';
                    } catch (Exception $e) {
                        // Afișăm mesajul corespunzător în cazul în care apare o altă excepție
                        echo '<div class="alert alert-danger"><em>Oops! Ceva a mers greșit. Vă rugăm să încercați din nou.</em></div>';
                    }
 
                    // Închidem conecțiunea
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>