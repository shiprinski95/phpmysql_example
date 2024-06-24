<!-- Specificăm standardul HTML5 -->
<!DOCTYPE html>

<!-- Deschidem documentul HTML și stabilim limba română -->
<html lang="ro">
  <!-- Secțiunea declarativă cu titlu și metainformații -->
  <head>
    <!-- Unicode pentru diacritice -->
    <meta charset="utf-8">
    <!-- Pentru ca site-ul să fie responsiv pe dispozitivele mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stabilirea titlului documentului -->
    <title>Autentificare</title>
    <!-- Specificăm Style sheet-ul (CSS) intern -->
    <style>
      body {
	  /* Lista cu font-uri */
          font-family: Arial, sans-serif;
	  /* Culoarea fundalului */
          background-color: #F4F4F4;
	  /* Fără spații la margini */
          margin: 0;
          padding: 0;
          display: flex;
	  /* Alinierea conținutului la mijloc */
          justify-content: center;
          align-items: center;
	  /* Înălțimea în unități vh */
          height: 100vh;
      }

      /* Container-ul de logare */
      .login-container {
	  /* Culoarea fundalului */
          background-color: #FFF;
	  /* Colțuri rotungite */
          border-radius: 8px;
	  /* Umbră cu opacitate 10% */
          box-shadow: 0 2px 4px #0000001A;
	  /* Spațiu între conținut */
          padding: 20px;
	  /* Lățime maximă */
          max-width: 350px;
	  /* Lățimea în procente */
          width: 100%;
      }

      /* Header-ul de nivelul 2 */
      .login-container h2 {
          /* Lipsa spațiului de sus */
          margin-top: 0;
	  /* Alinierea textului la centru */
          text-align: center;
      }

      /* Câmpurile de introducere */
      .login-container input[type="text"],
      .login-container input[type="password"] {
	  /* Lățimea în procente */
          width: 100%;
	  /* Spațiu între conținut */
          padding: 10px;
	  /* Spațiu între ele */
          margin: 10px 0;
	  /* Grosimea și culoarea marginii */
          border: 1px solid #CCC;
	  /* Colțuri rotungite */
          border-radius: 4px;
          box-sizing: border-box;
      }

      /* Butanele de confirmare și resetare */
      .login-container input[type="submit"],
      .login-container input[type="reset"] {
	  /*
	     Lățimea în procente (jumătate)
	     2% au rămas ca spațiu între ele
          */
          width: 49%;
	  /* Culoarea fundalului */
          background-color: #4CAF50;
	  /* Culoarea textului */
          color: #FFF;
	  /* Spațiu între conținut */
          padding: 10px;
	  /* Fără margine */
          border: none;
	  /* Colțuri rotungite */
          border-radius: 4px;
          cursor: pointer;
      }

      /* Stabilim nuanță roșietică */
      .login-container input[type="reset"] {
	  /* Culoarea fundalului */
          background-color: #E53935;
      }

      /* Când mouse-ul este deasupra */
      .login-container input[type="submit"]:hover {
	  /* Schimbăm culoarea fundalului */
          background-color: #45A049;
      }

      /* Când mouse-ul este deasupra */
      .login-container input[type="reset"]:hover {
	  /* Schimbăm culoarea fundalului */
          background-color: #C62828;
      }

      /* Paragraf din cadrul container-ului */
      .login-container p {
	  /* Spațiu de la margină */
          margin: 10px 0;
	  /* Alinierea textului la centru */
          text-align: center;
      }
    </style>
  </head>

  <!-- Secțiunea corpului conținutului -->
  <body>
    <!-- Container-ul de logare -->
    <div class="login-container">
      <h2>Autentificare</h2>
      <form action="parola.php" method="POST">
        <input type="text" name="username" placeholder="Nume utilizator" required>
        <input type="password" name="parola" placeholder="Parolă" required>
        <input type="submit" value="Autentificare" id="loginbutton" name="Submit">
        <input type="reset" value="Resetare">
      </form>
      <!--
          Afișarea valorilor variabilelor predefinite PHP prin
          combinarea codului HTML cu PHP prin tag-ul PHP și comanda echo
          P. S. Am utilizat funcția htmlspecialchars() pentru a escăpa
          caracterele speciale HTML (împotriva atacurilor XSS)
      -->
      <p>
        Nume server: <b><?php echo htmlspecialchars($_SERVER['SERVER_NAME']); ?></b><br>
	<!-- Afișează numele sistemului de operare -->
	Platformă: <b><?php echo htmlspecialchars($_SERVER['HTTP_SEC_CH_UA_PLATFORM']); ?></b><br>
        Protocol: <b><?php echo htmlspecialchars($_SERVER['SERVER_PROTOCOL']); ?></b><br>
        Server port: <b><?php echo htmlspecialchars($_SERVER['SERVER_PORT']); ?></b><br>
        Sofware server: <b><?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE']); ?></b><br>
        Nume script:<br><b><?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?></b><br>
        <!-- Afișează data și ora curentă în formatul specificat -->
        Data și ora curentă: <b><?php echo htmlspecialchars(date("d-m-Y H:i:s")); ?></b>
      </p>
    </div>
  </body>
</html>
