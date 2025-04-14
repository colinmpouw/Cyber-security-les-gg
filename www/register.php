<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); 
    $password = $_POST['password']; 

    if (empty($username) || empty($password)) {
        echo "Gebruikersnaam en wachtwoord zijn verplicht!";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "Ongeldige gebruikersnaam!";
        exit();
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Registratie succesvol! <a href='index.php'>Login hier</a>";
    } else {
        echo "Fout: " . $stmt->error;
    }

    $stmt->close();
}
?>

<h2>Registreren</h2>
<form method="POST">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <input type="submit" value="Registreren">
</form>