<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "Deze gebruikersnaam is al in gebruik!";
        exit();
    }


    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')"; 
    if ($conn->query($sql) === TRUE) {
        echo "Registratie succesvol! <a href='index.php'>Login hier</a>";
    } else {
        echo "Fout: " . $conn->error;
    }
}
?>

<h2>Registreren</h2>
<form method="POST">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <input type="submit" value="Registreren">
</form>