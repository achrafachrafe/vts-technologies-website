
<?php
require_once __DIR__ . '/../connexion.php';

session_start();

$message = '';

$count = (int) $pdo->query(
    "SELECT COUNT(*) FROM admins"
)->fetchColumn();

if ($count > 0) {
    exit("Le compte administrateur existe déjà. Supprimez ou protégez setup.php.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (strlen($username) < 3) {
        $message = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    } elseif (strlen($password) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO admins (username, password) VALUES (?, ?)"
        );
        $stmt->execute([$username, $hash]);

        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un administrateur | VTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:480px">
    <div class="card shadow border-0">
        <div class="card-body p-4">
            <h2 class="mb-2">VTS Technologies</h2>
            <p class="text-secondary">Créer le compte administrateur</p>

            <?php if ($message): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label class="form-label">Nom d'utilisateur</label>
                <input class="form-control mb-3" name="username"
                       minlength="3" required>

                <label class="form-label">Mot de passe</label>
                <input class="form-control mb-3" type="password"
                       name="password" minlength="8" required>

                <label class="form-label">Confirmer le mot de passe</label>
                <input class="form-control mb-3" type="password"
                       name="confirm" minlength="8" required>

                <button class="btn btn-dark w-100">
                    Créer le compte
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
