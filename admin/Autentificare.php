<?php
session_start();

// informatii conectare
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'store';
$DATABASE_PORT = '3306';

// Încercați să vă conectați folosind informațiile de mai sus
$con = mysqli_connect(
    $DATABASE_HOST,
    $DATABASE_USER,
    $DATABASE_PASS,
    $DATABASE_NAME,
    $DATABASE_PORT
);

if (mysqli_connect_errno()) 
{
    // Dacă există o eroare la conexiune, opriți scriptul și afișați eroarea
    exit('Esec conectare MySQL: ' . mysqli_connect_error());
}

// Acum verificăm dacă datele din formularul de autentificare au fost trimise, isset () va verifica dacă datele există
if (!isset($_POST['username'], $_POST['password'])) 
{
    // Nu s-au putut obține datele care ar fi trebuit trimise
    exit('Completati username si password !');
}

// Pregătiți SQL-ul nostru, pregătirea instrucțiunii SQL va împiedica injecția SQL
if ($stmt = $con->prepare('SELECT id, password FROM angajati WHERE username = ?')) 
{
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Stocați rezultatul astfel încât să putem verifica dacă contul există în baza de date
    $stmt->store_result();

    if ($stmt->num_rows > 0) 
    {
        $stmt->bind_result($id, $password);
        $stmt->fetch();

        // Contul există, acum verificăm parola
        if ($_POST['password'] == $password) 
        {
            // Creați sesiuni, astfel încât să știm că utilizatorul este conectat, acestea acționează practic ca cookie-//uri, dar rețin datele de pe server
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            echo 'Bine ati venit' . $_SESSION['name'] . '!';
            header('Location: Vizualizare.php');
        } 
        else 
        {
            echo 'Incorrect password!';
        }
    } 
    else 
    {
        echo 'Incorrect username !';
    }

    $stmt->close();
}
?>
