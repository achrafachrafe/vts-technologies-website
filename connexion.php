
<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Database connection failed.');
}

$conn->set_charset('utf8mb4');
?>
<?php

$host = "localhost";
$dbname = "vts_admin";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    $pdo->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
    );

} catch (PDOException $e) {

    error_log($e->getMessage());

    exit("Erreur : impossible de se connecter à la base de données.");

}
?>
