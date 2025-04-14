<?php
include 'db.php';

// Zet foutmeldingen aan
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Drop tabel als hij bestaat
    $conn->query("DROP TABLE IF EXISTS users");

    // Maak de tabel opnieuw aan
    $conn->query("
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    ");

    // Hash het wachtwoord voor veilige opslag
    $hashedPassword = password_hash('testpass', PASSWORD_DEFAULT);

    // Voeg standaard gebruiker toe met een prepared statement
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    $username = 'testuser';
    $stmt->execute();

    echo "Database reset succesvol!";
} catch (Exception $e) {
    echo "Fout bij resetten: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

$conn->close();
?>
