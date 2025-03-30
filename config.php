<?php
// config.php - Configuration de la base de données avec PDO
$host = 'localhost';
$dbname = 'salon_coiffure';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Création des tables SQL
$pdo->exec("CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('client', 'coiffeur', 'admin') NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    duree INT NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    coiffeur_id INT NOT NULL,
    service_id INT NOT NULL,
    date_rdv DATETIME NOT NULL,
    statut ENUM('confirmé', 'annulé', 'en attente') DEFAULT 'en attente',
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (coiffeur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_paiement DATETIME NOT NULL,
    statut ENUM('payé', 'en attente') DEFAULT 'en attente',
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);");

// Inscription des utilisateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // Take the selected role from the form

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
    
    $stmt->execute([$nom, $email, $mot_de_passe, $role]);
    echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
}

// Connexion des utilisateurs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        echo "Connexion réussie.";
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    echo "Déconnexion réussie.";
}

// Message de confirmation
echo "Base de données et tables créées avec succès !";
?>
