
<?php
require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/includes/auth.php';

$servicesCount = (int) $pdo->query(
    "SELECT COUNT(*) FROM services"
)->fetchColumn();

$projectsCount = (int) $pdo->query(
    "SELECT COUNT(*) FROM projects"
)->fetchColumn();

$messagesCount = (int) $pdo->query(
    "SELECT COUNT(*) FROM messages"
)->fetchColumn();

$unreadCount = (int) $pdo->query(
    "SELECT COUNT(*) FROM messages WHERE is_read = 0"
)->fetchColumn();

$adminsCount = (int) $pdo->query(
    "SELECT COUNT(*) FROM admins"
)->fetchColumn();

$recentMessages = $pdo->query(
    "SELECT id, name, email, subject, created_at
     FROM messages
     ORDER BY id DESC
     LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | VTS Technologies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <a class="sidebar-brand" href="index.php">VTS<span>.</span></a>
        <p class="sidebar-label">MENU PRINCIPAL</p>

        <a class="nav-item active" href="index.php">▦ Dashboard</a>
        <a class="nav-item" href="messages.php">✉ Messages
            <?php if ($unreadCount > 0): ?>
                <span class="nav-count"><?= $unreadCount ?></span>
            <?php endif; ?>
        </a>
        <a class="nav-item" href="services.php">⚙ Services</a>
        <a class="nav-item" href="projects.php">▣ Projets</a>
        <a class="nav-item" href="users.php">♙ Administrateurs</a>
        <a class="nav-item" href="settings.php">⚙ Paramètres</a>

        <div class="sidebar-bottom">
            <div class="small text-white-50 mb-2">
                Connecté : <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
            </div>
            <a class="nav-item logout-link" href="logout.php">↪ Déconnexion</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Bienvenue dans votre espace de gestion VTS.</p>
            </div>
            <span class="admin-badge">Administrateur</span>
        </header>

        <section class="stats-grid">
            <article class="stat-card">
                <span class="stat-icon">⚙</span>
                <p>Services</p>
                <h2><?= $servicesCount ?></h2>
                <small>Services enregistrés</small>
            </article>

            <article class="stat-card">
                <span class="stat-icon">▣</span>
                <p>Projets</p>
                <h2><?= $projectsCount ?></h2>
                <small>Projets enregistrés</small>
            </article>

            <article class="stat-card">
                <span class="stat-icon">✉</span>
                <p>Messages</p>
                <h2><?= $messagesCount ?></h2>
                <small><?= $unreadCount ?> non lus</small>
            </article>

            <article class="stat-card">
                <span class="stat-icon">♙</span>
                <p>Administrateurs</p>
                <h2><?= $adminsCount ?></h2>
                <small>Comptes enregistrés</small>
            </article>
        </section>

        <section class="content-card">
            <div class="section-heading">
                <div>
                    <h3>Messages récents</h3>
                    <p>Derniers messages reçus depuis le site.</p>
                </div>
                <a class="btn btn-primary" href="messages.php">Voir les messages</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Sujet</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!$recentMessages): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-secondary">
                                Aucun message pour le moment.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentMessages as $msg): ?>
                            <tr>
                                <td><?= htmlspecialchars($msg['name']) ?></td>
                                <td><?= htmlspecialchars($msg['email']) ?></td>
                                <td><?= htmlspecialchars($msg['subject'] ?? '') ?></td>
                                <td><?= htmlspecialchars($msg['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
