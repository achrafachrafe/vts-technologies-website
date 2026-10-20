
<?php
require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($action === 'save') {
        if (strlen($username) < 3 || strlen($username) > 100) {
            $error = "Le nom d'utilisateur doit contenir entre 3 et 100 caractères.";
        } elseif ($id && $password !== '' && strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (!$id && strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
        } else {
            try {
                if ($id) {
                    if ($password !== '') {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare(
                            "UPDATE admins SET username=?, password=? WHERE id=?"
                        );
                        $stmt->execute([$username, $hash, $id]);
                    } else {
                        $stmt = $pdo->prepare(
                            "UPDATE admins SET username=? WHERE id=?"
                        );
                        $stmt->execute([$username, $id]);
                    }

                    if ($id === (int)$_SESSION['admin_id']) {
                        $_SESSION['admin_username'] = $username;
                    }
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare(
                        "INSERT INTO admins (username, password) VALUES (?, ?)"
                    );
                    $stmt->execute([$username, $hash]);
                }

                header('Location: users.php');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    $error = "Ce nom d'utilisateur existe déjà.";
                } else {
                    throw $e;
                }
            }
        }
    } elseif ($action === 'delete' && $id) {
        if ($id === (int)$_SESSION['admin_id']) {
            $error = "Vous ne pouvez pas supprimer votre propre compte.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM admins WHERE id=?");
            $stmt->execute([$id]);

            header('Location: users.php');
            exit;
        }
    }
}

$edit = null;
$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);

if ($editId) {
    $stmt = $pdo->prepare(
        "SELECT id, username FROM admins WHERE id=?"
    );
    $stmt->execute([$editId]);
    $edit = $stmt->fetch();
}

$admins = $pdo->query(
    "SELECT id, username FROM admins ORDER BY id DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrateurs | VTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<main class="main-content" style="margin-left:0">
    <a href="index.php">← Dashboard</a>
    <h1 class="my-4">Gestion des administrateurs</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="content-card mb-4">
        <h3><?= $edit ? 'Modifier le compte' : 'Créer un administrateur' ?></h3>
        <form method="POST" class="row g-3 mt-1">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">

            <div class="col-md-6">
                <label class="form-label">Nom d'utilisateur</label>
                <input class="form-control" name="username" minlength="3" maxlength="100"
                       value="<?= htmlspecialchars($edit['username'] ?? '') ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Mot de passe <?= $edit ? '(laisser vide pour garder le même)' : '' ?>
                </label>
                <input class="form-control" type="password" name="password"
                       minlength="8" <?= $edit ? '' : 'required' ?>
                       autocomplete="new-password">
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Enregistrer</button>
                <?php if ($edit): ?>
                    <a href="users.php" class="btn btn-secondary">Annuler</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-card table-responsive">
        <table class="table align-middle">
            <thead><tr><th>ID</th><th>Nom d'utilisateur</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($admins as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= htmlspecialchars($a['username']) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="?edit=<?= (int)$a['id'] ?>">Modifier</a>
                        <?php if ((int)$a['id'] !== (int)$_SESSION['admin_id']): ?>
                            <form method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer cet administrateur ?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                                <button class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Compte actuel</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
