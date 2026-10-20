<?php
require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/includes/auth.php';
$defaults = [
    'site_name' => 'VTS Technologies',
    'site_email' => '',
    'site_phone' => '',
    'site_description' => 'Digital & Software Solutions'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($defaults as $key => $default) {
        $value = trim($_POST[$key] ?? '');

        $stmt = $pdo->prepare(
            "INSERT INTO settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        );
        $stmt->execute([$key, $value]);
    }

    header('Location: settings.php?saved=1');
    exit;
}

$settings = $defaults;

$rows = $pdo->query(
    "SELECT setting_key, setting_value FROM settings"
)->fetchAll();

foreach ($rows as $row) {
    if (array_key_exists($row['setting_key'], $defaults)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paramètres | VTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <main class="main-content" style="margin-left:0">
    <a href="index.php">← Dashboard</a>
    <h1 class="my-4">Paramètres du site</h1>

    <div class="content-card">
        <?php if (isset($_GET['saved'])): ?>
            <div class="alert alert-success">Paramètres enregistrés avec succès.</div>
        <?php endif; ?>

        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nom du site</label>
                <input class="form-control" name="site_name" maxlength="150"
                       value="<?= htmlspecialchars($settings['site_name']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email professionnel</label>
                <input class="form-control" type="email" name="site_email" maxlength="150"
                       value="<?= htmlspecialchars($settings['site_email']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input class="form-control" name="site_phone" maxlength="40"
                       value="<?= htmlspecialchars($settings['site_phone']) ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="site_description" rows="4"><?= htmlspecialchars($settings['site_description']) ?></textarea>
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Enregistrer les paramètres</button>
            </div>
        </form>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const alertMessage = document.querySelector('.alert-success');

    if (alertMessage) {
        setTimeout(function () {
            alertMessage.style.transition = 'opacity 0.5s ease';
            alertMessage.style.opacity = '0';

            setTimeout(function () {
                alertMessage.remove();
            }, 500);
        }, 5000);
    }
});
</script>
</body>
</html>
