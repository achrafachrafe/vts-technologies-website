<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../connexion.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$error = '';
$editService = null;

/* =========================
   ADD / UPDATE / DELETE
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add' || $action === 'update') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $icon = trim($_POST['icon'] ?? '');
            $features = trim($_POST['features'] ?? '');
            $technologies = trim($_POST['technologies'] ?? '');
            $serviceNumber = trim($_POST['service_number'] ?? '');
            $badge = trim($_POST['badge'] ?? '');
            $linkText = trim($_POST['link_text'] ?? '');
            $linkUrl = trim($_POST['link_url'] ?? '');
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

            if ($title === '') {
                throw new RuntimeException('Le titre est obligatoire.');
            }

            if ($action === 'add') {
                $sql = "INSERT INTO services
                    (title, description, icon, features, technologies,
                     service_number, badge, link_text, link_url, is_featured)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $title, $description, $icon, $features, $technologies,
                    $serviceNumber, $badge, $linkText, $linkUrl, $isFeatured
                ]);

                header('Location: services.php?success=added');
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);

            $sql = "UPDATE services SET
                    title = ?, description = ?, icon = ?, features = ?,
                    technologies = ?, service_number = ?, badge = ?,
                    link_text = ?, link_url = ?, is_featured = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title, $description, $icon, $features, $technologies,
                $serviceNumber, $badge, $linkText, $linkUrl,
                $isFeatured, $id
            ]);

            header('Location: services.php?success=updated');
            exit;
        }

        if ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);

            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$id]);

            header('Location: services.php?success=deleted');
            exit;
        }
    } catch (PDOException $ex) {
        error_log($ex->getMessage());
        $error = 'Erreur de base de données. Vérifie les colonnes de la table services.';
    } catch (RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

/* =========================
   SERVICE TO EDIT
========================= */

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $editService = $stmt->fetch();

    if (!$editService) {
        header('Location: services.php');
        exit;
    }
}

/* =========================
   GET ALL SERVICES
========================= */

$services = $pdo->query(
    "SELECT * FROM services ORDER BY id DESC"
)->fetchAll();

$isEditing = is_array($editService);
$form = $editService ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des services | VTS Admin</title>

    <link rel="stylesheet" href="assets/css/admin.css">

    <style>
        body { font-family: Arial, sans-serif; }
        .services-page { padding: 25px; max-width: 1200px; margin: auto; }
        .service-form, .service-table {
            background: #fff; padding: 22px; border-radius: 12px;
            box-shadow: 0 3px 14px #0000000d; margin-bottom: 25px;
        }
        .service-form h2, .service-table h2 { margin-top: 0; }
        .form-grid {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .field { display: flex; flex-direction: column; gap: 7px; }
        .field.full { grid-column: 1 / -1; }
        .field input, .field textarea {
            padding: 11px; border: 1px solid #ddd; border-radius: 7px;
            font: inherit; width: 100%; box-sizing: border-box;
        }
        .field textarea { min-height: 95px; resize: vertical; }
        .check-field { display: flex; align-items: center; gap: 9px; }
        .btn {
            display: inline-block; padding: 10px 15px; border: 0;
            border-radius: 7px; cursor: pointer; text-decoration: none;
            font-size: 14px; margin: 3px;
        }
        .btn-primary { background: #2563eb; color: white; }
        .btn-edit { background: #e0edff; color: #1649a0; }
        .btn-delete { background: #fee2e2; color: #991b1b; }
        .btn-cancel { background: #eee; color: #222; }
        .notice { padding: 12px; margin-bottom: 15px; border-radius: 7px; }
        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8fafc; }
        .service-icon { font-size: 22px; color: #2563eb; }
        @media (max-width: 700px) {
            .services-page { padding: 12px; }
            .form-grid { grid-template-columns: 1fr; }
            .field.full { grid-column: auto; }
        }
    </style>
</head>

<body>
<main class="services-page">

    <h1>Gestion des services</h1>
    <p>Ajoute, modifie et supprime les services de VTS Technologies.</p>

    <?php if ($error !== ''): ?>
        <div class="notice error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="notice success">
            <?php
            $messages = [
                'added' => 'Service ajouté avec succès.',
                'updated' => 'Service modifié avec succès.',
                'deleted' => 'Service supprimé avec succès.'
            ];
            echo e($messages[$_GET['success']] ?? 'Opération terminée.');
            ?>
        </div>
    <?php endif; ?>

    <!-- FORMULAIRE -->
    <section class="service-form">
        <h2><?= $isEditing ? 'Modifier le service' : 'Ajouter un service' ?></h2>

        <form method="POST" action="services.php">
            <input type="hidden" name="action"
                   value="<?= $isEditing ? 'update' : 'add' ?>">

            <?php if ($isEditing): ?>
                <input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">

                <div class="field">
                    <label for="title">Nom du service *</label>
                    <input id="title" name="title" required
                           value="<?= e($form['title'] ?? '') ?>"
                           placeholder="Web Development">
                </div>

                <div class="field">
                    <label for="icon">Icône Font Awesome</label>
                    <input id="icon" name="icon"
                           value="<?= e($form['icon'] ?? '') ?>"
                           placeholder="fa-solid fa-code">
                </div>

                <div class="field full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                              placeholder="Description du service..."><?= e($form['description'] ?? '') ?></textarea>
                </div>

                <div class="field full">
                    <label for="features">Features (une par ligne)</label>
                    <textarea id="features" name="features"
                              placeholder="Business Websites&#10;Landing Pages&#10;Responsive Design&#10;SEO Ready"><?= e($form['features'] ?? '') ?></textarea>
                </div>

                <div class="field full">
                    <label for="technologies">Technologies</label>
                    <input id="technologies" name="technologies"
                           value="<?= e($form['technologies'] ?? '') ?>"
                           placeholder="HTML · CSS · JavaScript · PHP">
                </div>

                <div class="field">
                    <label for="service_number">Numéro du service</label>
                    <input id="service_number" name="service_number"
                           value="<?= e($form['service_number'] ?? '') ?>"
                           placeholder="01">
                </div>

                <div class="field">
                    <label for="badge">Badge (optionnel)</label>
                    <input id="badge" name="badge"
                           value="<?= e($form['badge'] ?? '') ?>"
                           placeholder="Popular">
                </div>

                <div class="field">
                    <label for="link_text">Texte du bouton</label>
                    <input id="link_text" name="link_text"
                           value="<?= e($form['link_text'] ?? '') ?>"
                           placeholder="Discuss Your Project">
                </div>

                <div class="field">
                    <label for="link_url">Lien du bouton</label>
                    <input id="link_url" name="link_url"
                           value="<?= e($form['link_url'] ?? '') ?>"
                           placeholder="web-development.html">
                </div>

                <div class="field full">
                    <label class="check-field">
                        <input type="checkbox" name="is_featured" value="1"
                            <?= !empty($form['is_featured']) ? 'checked' : '' ?>>
                        Mettre ce service en vedette
                    </label>
                </div>

            </div>

            <br>

            <button class="btn btn-primary" type="submit">
                <?= $isEditing ? 'Enregistrer les modifications' : 'Ajouter le service' ?>
            </button>

            <?php if ($isEditing): ?>
                <a class="btn btn-cancel" href="services.php">Annuler</a>
            <?php endif; ?>
        </form>
    </section>

    <!-- LISTE DES SERVICES -->
    <section class="service-table">
        <h2>Mes services (<?= count($services) ?>)</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Icône</th>
                        <th>Service</th>
                        <th>Technologies</th>
                        <th>Vedette</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!$services): ?>
                    <tr>
                        <td colspan="6">Aucun service pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= (int)$service['id'] ?></td>
                            <td class="service-icon">
                                <i class="<?= e($service['icon'] ?: 'fa-solid fa-code') ?>"></i>
                            </td>
                            <td><?= e($service['title']) ?></td>
                            <td><?= e($service['technologies'] ?? '') ?></td>
                            <td><?= !empty($service['is_featured']) ? 'Oui' : 'Non' ?></td>
                            <td>
                                <a class="btn btn-edit"
                                   href="services.php?edit=<?= (int)$service['id'] ?>">
                                    Modifier
                                </a>

                                <form method="POST" action="services.php"
                                      style="display:inline"
                                      onsubmit="return confirm('Supprimer ce service ?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id"
                                           value="<?= (int)$service['id'] ?>">
                                    <button class="btn btn-delete" type="submit">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <p><a href="index.php">← Retour au Dashboard</a></p>

</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.notice').forEach(function (message) {
        setTimeout(function () {
            message.style.transition = 'opacity 0.5s ease';
            message.style.opacity = '0';

            setTimeout(function () {
                message.remove();
            }, 500);
        }, 5000);
    });
});
</script>
</body>
</html>

