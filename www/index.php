<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // specialchar

    $username = htmlspecialchars($_POST['username']);
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Ongeldige login!";
        }
    } else {
        echo "Ongeldige login!";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
<p><a href="register.php">Registreer een account</a></p>