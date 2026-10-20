
<?php
require_once __DIR__ . '/../connexion.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT id, username, password FROM admins WHERE username = ? LIMIT 1"
    );
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        header('Location: index.php');
        exit;
    }

    $error = "Nom d'utilisateur ou mot de passe incorrect.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin | VTS Technologies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="login-page">
<div class="login-card">
    <div class="brand-mark">VTS</div>
    <h2>Bienvenue</h2>
    <p class="text-secondary">Connectez-vous à votre espace admin.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label class="form-label">Nom d'utilisateur</label>
        <input class="form-control mb-3" name="username"
               autocomplete="username" required>

        <label class="form-label">Mot de passe</label>
        <input class="form-control mb-4" type="password" name="password"
               autocomplete="current-password" required>

        <button class="btn btn-primary w-100">Se connecter</button>
    </form>
    <p class="small text-secondary text-center mt-4 mb-0">
        VTS Technologies | Digital &amp; Software Solutions
    </p>
</div>
</body>
</html>
