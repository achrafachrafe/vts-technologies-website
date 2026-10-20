<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../connexion.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$message = '';
$editProject = null;

/* =========================
   SUPPRIMER UN PROJET
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int) $_POST['delete'];

    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: projects.php?msg=deleted");
    exit;
}

/* =========================
   AJOUTER / MODIFIER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_project'])) {
    $id = (int) ($_POST['id'] ?? 0);

    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image       = trim($_POST['image'] ?? '');
    $features    = trim($_POST['features'] ?? '');
    $technologies = trim($_POST['technologies'] ?? '');
    $liveDemo    = trim($_POST['live_demo'] ?? '');
    $projectUrl  = trim($_POST['project_url'] ?? '');

    if ($title === '' || $description === '') {
        $message = "Le titre et la description sont obligatoires.";
    } else {
        if ($id > 0) {
            $sql = "UPDATE projects SET
                        title = ?,
                        category = ?,
                        description = ?,
                        image = ?,
                        features = ?,
                        technologies = ?,
                        live_demo = ?,
                        project_url = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title,
                $category,
                $description,
                $image,
                $features,
                $technologies,
                $liveDemo,
                $projectUrl,
                $id
            ]);

            header("Location: projects.php?msg=updated");
            exit;
        } else {
            $sql = "INSERT INTO projects
                        (title, category, description, image, features,
                         technologies, live_demo, project_url)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title,
                $category,
                $description,
                $image,
                $features,
                $technologies,
                $liveDemo,
                $projectUrl
            ]);

            header("Location: projects.php?msg=added");
            exit;
        }
    }
}

/* =========================
   CHARGER UN PROJET À MODIFIER
========================= */
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $editProject = $stmt->fetch();

    if (!$editProject) {
        $message = "Projet introuvable.";
    }
}

/* =========================
   LISTE DES PROJETS
========================= */
$stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
$projects = $stmt->fetchAll();

if (isset($_GET['msg'])) {
    $messages = [
        'added'   => 'Projet ajouté avec succès.',
        'updated' => 'Projet modifié avec succès.',
        'deleted' => 'Projet supprimé avec succès.'
    ];

    if (isset($messages[$_GET['msg']])) {
        $message = $messages[$_GET['msg']];
    }
}

$form = $editProject ?: [
    'id' => '',
    'title' => '',
    'category' => '',
    'description' => '',
    'image' => '',
    'features' => '',
    'technologies' => '',
    'live_demo' => '',
    'project_url' => ''
];
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des projets | VTS Admin</title>

    <link rel="stylesheet" href="assets/css/admin.css">

    <style>
        .projects-page {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .projects-page h1 {
            margin-bottom: 10px;
        }

        .admin-message {
            padding: 12px 16px;
            margin: 18px 0;
            border-radius: 8px;
            background: #e7f7ed;
            color: #176534;
        }

        .project-form {
            background: var(--card-bg, #fff);
            padding: 24px;
            border-radius: 12px;
            margin: 25px 0;
            box-shadow: 0 3px 15px rgba(0,0,0,.07);
        }

        .project-form label {
            display: block;
            margin: 15px 0 7px;
            font-weight: 600;
        }

        .project-form input,
        .project-form textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font: inherit;
            box-sizing: border-box;
        }

        .project-form textarea {
            min-height: 100px;
            resize: vertical;
        }

        .project-form button,
        .btn-edit,
        .btn-delete,
        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            text-decoration: none;
            font: inherit;
            margin-top: 12px;
        }

        .project-form button {
            background: #6c4cff;
            color: white;
        }

        .btn-back {
            background: #333;
            color: white;
        }

        .btn-edit {
            background: #e8efff;
            color: #244db3;
        }

        .btn-delete {
            background: #ffe8e8;
            color: #b42318;
        }

        .projects-table-wrapper {
            overflow-x: auto;
        }

        .projects-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-bg, #fff);
        }

        .projects-table th,
        .projects-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }

        .projects-table th {
            background: #f3f3f7;
        }

        .project-thumb {
            width: 90px;
            height: 65px;
            object-fit: cover;
            border-radius: 6px;
        }

        .project-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .project-actions form {
            margin: 0;
        }

        .project-actions .btn-delete {
            margin-top: 12px;
        }

        .form-note {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="projects-page">
    <a href="index.php" class="btn-back">← Retour au Dashboard</a>

    <h1>Gestion des projets</h1>
    <p>Ajoute, modifie ou supprime les projets affichés sur ton site VTS.</p>

    <?php if ($message !== ''): ?>
        <div class="admin-message"><?= e($message) ?></div>
    <?php endif; ?>

    <section class="project-form">
        <h2>
            <?= $editProject ? 'Modifier le projet' : 'Ajouter un projet' ?>
        </h2>

        <form method="POST" action="projects.php">
            <input type="hidden" name="id" value="<?= e($form['id']) ?>">

            <label for="title">Titre du projet *</label>
            <input
                type="text"
                id="title"
                name="title"
                required
                value="<?= e($form['title']) ?>"
                placeholder="Ex : V&A Pâtisserie"
            >

            <label for="category">Catégorie</label>
            <input
                type="text"
                id="category"
                name="category"
                value="<?= e($form['category']) ?>"
                placeholder="Ex : E-Commerce"
            >

            <label for="description">Description *</label>
            <textarea
                id="description"
                name="description"
                required
                placeholder="Décris le projet..."
            ><?= e($form['description']) ?></textarea>

            <label for="image">Chemin de l'image</label>
            <input
                type="text"
                id="image"
                name="image"
                value="<?= e($form['image']) ?>"
                placeholder="Ex : assets/projects/ya-patisserie.jpg"
            >
            <p class="form-note">
                Entre le chemin relatif de l'image par rapport à la racine du site.
            </p>

            <?php if (!empty($form['image'])): ?>
                <p>Image actuelle :</p>
                <img
                    class="project-thumb"
                    src="../<?= e($form['image']) ?>"
                    alt="Aperçu du projet"
                >
            <?php endif; ?>

            <label for="features">Fonctionnalités</label>
            <textarea
                id="features"
                name="features"
                placeholder="Une fonctionnalité par ligne"
            ><?= e($form['features']) ?></textarea>

            <label for="technologies">Technologies utilisées</label>
            <input
                type="text"
                id="technologies"
                name="technologies"
                value="<?= e($form['technologies']) ?>"
                placeholder="HTML, CSS, JavaScript, PHP, MySQL"
            >

            <label for="live_demo">Lien de démonstration</label>
            <input
                type="url"
                id="live_demo"
                name="live_demo"
                value="<?= e($form['live_demo']) ?>"
                placeholder="https://exemple.com"
            >

            <label for="project_url">Lien du projet / code source</label>
            <input
                type="text"
                id="project_url"
                name="project_url"
                value="<?= e($form['project_url']) ?>"
                placeholder="https://github.com/..."
            >

            <button type="submit" name="save_project">
                <?= $editProject ? 'Enregistrer les modifications' : 'Ajouter le projet' ?>
            </button>

            <?php if ($editProject): ?>
                <a href="projects.php" class="btn-back">Annuler</a>
            <?php endif; ?>
        </form>
    </section>

    <section>
        <h2>Les projets enregistrés (<?= count($projects) ?>)</h2>

        <div class="projects-table-wrapper">
            <table class="projects-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Technologies</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="6">Aucun projet enregistré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= e($project['id']) ?></td>

                            <td>
                                <?php if (!empty($project['image'])): ?>
                                    <img
                                        class="project-thumb"
                                        src="../<?= e($project['image']) ?>"
                                        alt="<?= e($project['title']) ?>"
                                    >
                                <?php else: ?>
                                    Aucune image
                                <?php endif; ?>
                            </td>

                            <td><?= e($project['title']) ?></td>
                            <td><?= e($project['category']) ?></td>
                            <td><?= e($project['technologies']) ?></td>

                            <td>
                                <div class="project-actions">
                                    <a
                                        class="btn-edit"
                                        href="projects.php?edit=<?= (int)$project['id'] ?>"
                                    >Modifier</a>

                                    <form
                                        method="POST"
                                        action="projects.php"
                                        onsubmit="return confirm('Confirmer la suppression de ce projet ?');"
                                    >
                                        <button
                                            class="btn-delete"
                                            type="submit"
                                            name="delete"
                                            value="<?= (int)$project['id'] ?>"
                                        >Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.admin-message').forEach(function (message) {
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
