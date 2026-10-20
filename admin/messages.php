
<?php
require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if ($id && $action === 'read') {
        $stmt = $pdo->prepare(
            "UPDATE messages SET is_read = 1 WHERE id = ?"
        );
        $stmt->execute([$id]);
    } elseif ($id && $action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->execute([$id]);
    }

    header('Location: messages.php');
    exit;
}

$messages = $pdo->query(
    "SELECT * FROM messages ORDER BY id DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages | VTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="main-content" style="margin-left:0">
    <a href="index.php">← Retour au Dashboard</a>
    <h1 class="my-4">Messages des visiteurs</h1>

    <div class="content-card table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($messages as $msg): ?>
                <tr>
                    <td><?= htmlspecialchars($msg['name']) ?></td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= htmlspecialchars($msg['subject'] ?? '') ?></td>
                    <td style="min-width:220px;max-width:350px;white-space:pre-wrap"><?= htmlspecialchars($msg['message']) ?></td>
                    <td>
                        <?= $msg['is_read'] ? 'Lu' : 'Non lu' ?>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <?php if (!$msg['is_read']): ?>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= (int)$msg['id'] ?>">
                                    <input type="hidden" name="action" value="read">
                                    <button class="btn btn-sm btn-primary">Lu</button>
                                </form>
                            <?php endif; ?>

                            <form method="POST" onsubmit="return confirm('Supprimer ce message ?')">
                                <input type="hidden" name="id" value="<?= (int)$msg['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (!$messages): ?>
                <tr><td colspan="6" class="text-center">Aucun message.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
