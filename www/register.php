<?php
include 'db.php';
session_start();

// CSRF-token genereren
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function showError($msg, $username = '') {
    echo '<div style="color:red;">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</div>';
    global $safe_username;
    $safe_username = $username;
}

$safe_username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF-check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        showError("Ongeldige aanvraag (CSRF)!");
        exit();
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        showError("Gebruikersnaam en wachtwoord zijn verplicht!", $username);
        exit();
    }

    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        showError("Wachtwoord moet minimaal 8 tekens bevatten, met hoofdletter, kleine letter en cijfer.", $username);
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $username)) {
        showError("Ongeldige gebruikersnaam! Alleen letters en cijfers, 3-20 tekens.", $username);
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        showError("Gebruikersnaam bestaat al!", $username);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Registratie succesvol! <a href='index.php'>Login hier</a>";
    } else {
        showError("Er is een fout opgetreden. Probeer het later opnieuw.", $username);
    }

    $stmt->close();
    unset($_SESSION['csrf_token']);
}
?>

<h2>Registreren</h2>
<form method="POST" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    Gebruikersnaam: <input type="text" name="username" required pattern="[a-zA-Z0-9]{3,20}" value="<?php echo htmlspecialchars($safe_username); ?>"><br>
    Wachtwoord: <input type="password" name="password" required minlength="8"><br>
    <input type="submit" value="Registreren">
</form>