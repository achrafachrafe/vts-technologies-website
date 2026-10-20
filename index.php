<?php
require_once __DIR__ . '/connexion.php';

$services = $pdo->query(
    "SELECT * FROM services ORDER BY id ASC"
)->fetchAll();

$projects = $pdo->query(
    "SELECT * FROM projects ORDER BY id DESC"
)->fetchAll();

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<?php

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["send_message"])) {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($name === "" || $email === "" ||
        $subject === "" || $message === "") {

        $error = "Veuillez remplir tous les champs.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Veuillez saisir une adresse email valide.";

    } else {
        try {
            $sql = "INSERT INTO messages
                    (name, email, subject, message)
                    VALUES (?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $name,
                $email,
                $subject,
                $message
            ]);

            $success = "Votre message a été envoyé avec succès !";

        } catch (PDOException $e) {
            $error = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
          <!-- ================= SEO ================= -->

<title>VTS Technologies | Digital & Software Solutions</title>

<meta
    name="description"
    content="VTS Technologies provides professional web development, e-commerce, web applications and custom digital solutions."
>

<meta
    name="keywords"
    content="VTS Technologies, web development, full stack developer, e-commerce, web applications, Morocco"
>

<meta name="author" content="VTS Technologies">

<meta name="robots" content="index, follow">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Canonical -->
<link
    rel="canonical"
    href="https://www.vtstechnologies.com/"
>

<!-- Favicon -->
<link
    rel="icon"
    type="image/png"
    href="assets/favicon.png"
>


<!-- ================= OPEN GRAPH ================= -->

<meta property="og:type" content="website">

<meta
    property="og:title"
    content="VTS Technologies | Digital & Software Solutions"
>

<meta
    property="og:description"
    content="Professional web development, e-commerce and custom digital solutions."
>

<meta
    property="og:url"
    content="https://www.vtstechnologies.com/"
>

<meta
    property="og:image"
    content="https://www.vtstechnologies.com/assets/og-image.jpg"
>

<meta
    property="og:site_name"
    content="VTS Technologies"
>

<!-- ================= TWITTER ================= -->

<meta name="twitter:card" content="summary_large_image">

<meta
    name="twitter:title"
    content="VTS Technologies | Digital & Software Solutions"
>

<meta
    name="twitter:description"
    content="Professional web development and digital solutions."
>

<meta
    name="twitter:image"
    content="https://www.vtstechnologies.com/assets/og-image.jpg"
>

<!-- ================= THEME ================= -->
<meta name="theme-color" content="#0b0b12">

<!-- ================= ORGANIZATION ================= -->

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",

    "name": "VTS Technologies",

    "url": "https://www.vtstechnologies.com/",

    "logo": "https://www.vtstechnologies.com/assets/logo.png",

    "description": "Digital and software solutions company specialized in web development, e-commerce and custom applications.",

    "sameAs": [
        "https://www.linkedin.com/",
        "https://www.fiverr.com/",
        "https://www.upwork.com/"
    ]
}
</script>

    <!-- CSS -->
    <link rel="stylesheet" href="css/styl.css">
</head>

<body class="light-mode">

<!-- ================= NAVBAR ================= -->


<header class="header">
    <nav class="navbar">

        <a href="#home" class="logo">
            <span>VTS</span>
            <small>Technologies</small>
        </a>

        <ul class="nav-links" id="seoNav">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#projects">Projects</a></li>
            <li><a href="#technologies">Technologies</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#process">Process</a></li>
            <li><a href="#why-vts">Why VTS</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-actions">

            <button id="themeBtn" class="icon-btn" type="button" aria-label="Changer le thème">
    <i class="fa-solid fa-moon"></i>
</button>

            <a href="#contact" class="nav-btn">
                Let's Talk
                <i class="fa-solid fa-arrow-right"></i>
            </a>

            <button id="seoMenuToggle"
                    class="menu-btn"
                    type="button"
                    aria-label="Ouvrir le menu"
                    aria-expanded="false"
                    aria-controls="seoNav">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>
    </nav>
</header>


<!-- ================= HOME ================= -->

<main>

<section id="home" class="hero">

    <div class="hero-content">

        <div class="hero-badge">
            <span></span>
            Digital & Software Solutions
        </div>

        <h1>
            We Build
            <span>Digital Experiences</span>
            That Make an Impact.
        </h1>

        <p>
            VTS Technologies creates modern websites, web applications
            and digital solutions that help businesses grow online.
        </p>

        <div class="hero-buttons">

            <a href="#projects" class="btn btn-primary">
                View Our Work
                <i class="fa-solid fa-arrow-right"></i>
            </a>

            <a href="#contact" class="btn btn-outline">
                Start a Project
            </a>

        </div>

        <div class="hero-stats">

            <div class="stat">
                <strong>20+</strong>
                <span>Projects</span>
            </div>

            <div class="stat">
                <strong>10+</strong>
                <span>Technologies</span>
            </div>

            <div class="stat">
                <strong>100%</strong>
                <span>Commitment</span>
            </div>

        </div>

    </div>


    <div class="hero-visual">

        <div class="code-window">

            <div class="window-top">

                <div class="window-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <span>VTS.dev</span>

            </div>

            <div class="code-content">

                <p>
                    <span class="purple">const</span>
                    <span class="blue">company</span>
                    =
                    <span class="green">"VTS Technologies"</span>;
                </p>

                <p>
                    <span class="purple">const</span>
                    <span class="blue">services</span> = [
                </p>

                <p class="indent">
                    <span class="green">"Web Development"</span>,
                </p>

                <p class="indent">
                    <span class="green">"E-Commerce"</span>,
                </p>

                <p class="indent">
                    <span class="green">"Web Applications"</span>,
                </p>

                <p class="indent">
                    <span class="green">"UI/UX Design"</span>,
                </p>

                 <p class="indent">
                    <span class="green">"Custom Software"</span>,
                </p>

                <p class="indent">
                    <span class="green">"Maintenance & Support"</span>,
                </p>

                <p class="indent">
                    <span class="green">"Mobile App Development"</span>,
                </p>

                <p class="indent">
                    <span class="green">"SEO & Digital Marketing"</span>,
                </p>

                <p class="indent">
                    <span class="green">"Graphic Design & Branding"</span>
                </p>

                <p>];</p>

                <p class="code-cursor">
                    <span class="purple">build</span>(future);
                </p>

            </div>

        </div>


        <div class="floating-card card-one">
            <i class="fa-solid fa-code"></i>
            <div>
                <strong>Clean Code</strong>
                <span>Modern Development</span>
            </div>
        </div>


        <div class="floating-card card-two">
            <i class="fa-solid fa-mobile-screen"></i>
            <div>
                <strong>Responsive</strong>
                <span>All Devices</span>
            </div>
        </div>

    </div>

</section>


<!-- ================= SERVICES ================= -->
<section class="services section" id="services">

    <div class="section-header">

        <span class="section-tag">Our Services</span>

        <h2>
            Digital Solutions
            <span>For Your Business</span>
        </h2>

        <p>
            We create modern digital solutions designed to help your
            business grow and succeed online.
        </p>

    </div>


<div class="services-grid">

    <?php foreach ($services as $service): ?>
        <?php
        $features = preg_split(
            '/\r\n|\r|\n/',
            $service['features'] ?? ''
        );
        ?>

        <div class="service-card <?= !empty($service['is_featured']) ? 'featured-service' : '' ?>">

            <?php if (!empty($service['badge'])): ?>
                <span class="service-badge">
                    <?= h($service['badge']) ?>
                </span>
            <?php endif; ?>

            <div class="service-icon">
                <i class="<?= h($service['icon'] ?: 'fa-solid fa-code') ?>"></i>
            </div>

            <?php if (!empty($service['service_number'])): ?>
                <span class="service-number">
                    <?= h($service['service_number']) ?>
                </span>
            <?php endif; ?>

            <h3><?= h($service['title']) ?></h3>

            <p><?= nl2br(h($service['description'])) ?></p>

            <?php if (!empty($service['features'])): ?>
                <ul>
                    <?php foreach ($features as $feature): ?>
                        <?php if (trim($feature) !== ''): ?>
                            <li><?= h(trim($feature)) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($service['technologies'])): ?>
                <div class="service-tech">
                    <?= h($service['technologies']) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($service['link_url'])): ?>
                <a
                    href="<?= h($service['link_url']) ?>"
                    class="service-link"
                >
                    <?= h($service['link_text'] ?: 'En savoir plus') ?>
                </a>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>

</section>

<!-- ================= PROJECTS ================= -->
<section class="projects section" id="projects">

    <div class="section-header">
        <span class="section-tag">Our Work</span>

        <h2>Featured <span>Projects</span></h2>

        <p>
            A selection of digital projects developed by VTS Technologies.
        </p>
    </div>
     <br>

<div class="projects-grid">

    <?php foreach ($projects as $project): ?>
        <?php
        $features = preg_split(
            '/\r\n|\r|\n/',
            $project['features'] ?? ''
        );

        $projectLink = !empty($project['live_demo'])
            ? $project['live_demo']
            : ($project['project_url'] ?? '');
        ?>

        <div class="project-card">

            <div class="project-image">

                <?php if (!empty($project['image'])): ?>
                    <img
                        src="<?= h($project['image']) ?>"
                        alt="<?= h($project['title']) ?>"
                        loading="lazy"
                    >
                <?php endif; ?>

                <div class="project-overlay">
                    <?php if ($projectLink !== ''): ?>
                        <a
                            href="<?= h($projectLink) ?>"
                            class="project-link"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Voir le projet
                        </a>
                    <?php endif; ?>
                </div>

            </div>

            <div class="project-content">

                <?php if (!empty($project['category'])): ?>
                    <span class="project-category">
                        <?= h($project['category']) ?>
                    </span>
                <?php endif; ?>

                <h3><?= h($project['title']) ?></h3>

                <p><?= nl2br(h($project['description'])) ?></p>

                <?php if (!empty($project['features'])): ?>
                    <ul class="project-features">
                        <?php foreach ($features as $feature): ?>
                            <?php if (trim($feature) !== ''): ?>
                                <li><?= h(trim($feature)) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($project['technologies'])): ?>
                    <div class="project-tech">
                        <?= h($project['technologies']) ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    <?php endforeach; ?>

</div>

</section>


<!-- ================= TECHNOLOGIES ================= -->

<section class="technologies" id="technologies">

    <div class="section-heading">

        <span class="section-tag">
            TECHNOLOGIES
        </span>

        <h2>
            Tools We <span>Use</span>
        </h2>

    </div>


    <div class="tech-grid">

        <div class="tech-item">
            <i class="fa-brands fa-html5"></i>
            <span>HTML5</span>
        </div>

        <div class="tech-item">
            <i class="fa-brands fa-css3-alt"></i>
            <span>CSS3</span>
        </div>

        <div class="tech-item">
            <i class="fa-brands fa-js"></i>
            <span>JavaScript</span>
        </div>

        <div class="tech-item">
            <i class="fa-brands fa-php"></i>
            <span>PHP</span>
        </div>

        <div class="tech-item">
            <i class="fa-solid fa-database"></i>
            <span>MySQL</span>
        </div>

        <div class="tech-item">
            <i class="fa-brands fa-laravel"></i>
            <span>Laravel</span>
        </div>

        <div class="tech-item">
            <i class="fa-brands fa-bootstrap"></i>
            <span>Bootstrap</span>
        </div>

        <div class="tech-item">
            <i class="fa-brands fa-git-alt"></i>
            <span>Git</span>
        </div>

    </div>

</section>


<!-- ================= ABOUT ================= -->

<section id="about" class="section about-section">

    <div class="about-content">

        <span class="section-tag">
            ABOUT VTS
        </span>

        <h2>
            Turning Ideas Into
            <span>Digital Solutions.</span>
        </h2>

        <p>
            VTS Technologies is a digital and software development
            company focused on creating modern, reliable and
            user-friendly digital solutions.
        </p>

        <p>
            From business websites to e-commerce platforms and
            custom web applications, we transform ideas into
            functional digital products.
        </p>

        <div class="about-features">

            <div>
                <i class="fa-solid fa-check"></i>
                <span>Modern & Responsive Design</span>
            </div>

            <div>
                <i class="fa-solid fa-check"></i>
                <span>Clean & Maintainable Code</span>
            </div>

            <div>
                <i class="fa-solid fa-check"></i>
                <span>Performance Focused</span>
            </div>

            <div>
                <i class="fa-solid fa-check"></i>
                <span>Client-Oriented Solutions</span>
            </div>

        </div>

    </div>


    <div class="about-card">

        <div class="about-logo">
            VTS
        </div>

        <h3>
            Digital & Software
            Solutions
        </h3>

        <p>
            Building the digital future,
            one project at a time.
        </p>

        <div class="about-line"></div>

        <span>
            Morocco · Worldwide
        </span>

    </div>

</section>


<!-- ================= CTA ================= -->

<section class="cta">

    <div>

        <span class="section-tag">
            HAVE A PROJECT?
        </span>

        <h2>
            Let's Build Something
            <span>Amazing.</span>
        </h2>

        <p>
            Tell us about your idea and let's turn it into
            a powerful digital experience.
        </p>

    </div>

    <a href="#contact" class="btn btn-primary">
        Start a Project
        <i class="fa-solid fa-arrow-right"></i>
    </a>

</section>

<!-- ================= PROCESS ================= -->
<section class="process section" id="process">
    <div class="section-header">
        <span class="section-tag">How We Work</span>
        <h2>Our <span>Process</span></h2>
        <p>
            A simple and professional process to turn your idea into
            a reliable digital solution.
        </p>
    </div>

    <div class="process-grid">

        <div class="process-card">
            <div class="process-number">01</div>
            <i class="fa-solid fa-comments"></i>
            <h3>Discovery</h3>
            <p>
                We understand your needs, goals and project requirements.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">02</div>
            <i class="fa-solid fa-pen-ruler"></i>
            <h3>Design</h3>
            <p>
                We create a modern, responsive and user-friendly interface.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">03</div>
            <i class="fa-solid fa-code"></i>
            <h3>Development</h3>
            <p>
                We build the website or application using modern technologies.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">04</div>
            <i class="fa-solid fa-vial"></i>
            <h3>Testing</h3>
            <p>
                We test performance, responsiveness, security and functionality.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">05</div>
            <i class="fa-solid fa-rocket"></i>
            <h3>Launch</h3>
            <p>
                Your project is prepared and deployed for real users.
            </p>
        </div>

        <div class="process-card">
            <div class="process-number">06</div>
            <i class="fa-solid fa-headset"></i>
            <h3>Support</h3>
            <p>
                We provide maintenance, improvements and technical support.
            </p>
        </div>

    </div>
</section>


<!-- ================= WHY VTS ================= -->
<section class="why-vts section" id="why-vts">

    <div class="section-header">
        <span class="section-tag">Why VTS</span>
        <h2>Why Choose <span>VTS Technologies?</span></h2>
        <p>
            We combine technology, creativity and business understanding
            to create useful digital solutions.
        </p>
    </div>

    <div class="why-grid">

        <div class="why-card">
            <i class="fa-solid fa-laptop-code"></i>
            <h3>Modern Technology</h3>
            <p>
                We use modern and reliable technologies to build scalable
                digital solutions.
            </p>
        </div>

        <div class="why-card">
            <i class="fa-solid fa-mobile-screen-button"></i>
            <h3>Responsive Design</h3>
            <p>
                Your website works perfectly on desktop, tablet and mobile.
            </p>
        </div>

        <div class="why-card">
            <i class="fa-solid fa-bolt"></i>
            <h3>Performance</h3>
            <p>
                We focus on speed, clean code and a smooth user experience.
            </p>
        </div>

        <div class="why-card">
            <i class="fa-solid fa-shield-halved"></i>
            <h3>Reliable Solutions</h3>
            <p>
                We build solutions with maintainability, security and
                reliability in mind.
            </p>
        </div>

    </div>
</section>


<!-- ================= PRICING ================= -->
<section class="pricing section" id="pricing">

    <div class="section-header">
        <span class="section-tag">Pricing</span>
        <h2>Simple <span>Packages</span></h2>
        <p>
            Flexible solutions for different project needs.
        </p>
    </div>

    <div class="pricing-grid">

        <div class="pricing-card">
            <div class="pricing-icon">
                <i class="fa-solid fa-globe"></i>
            </div>

            <h3>Starter</h3>
            <p class="pricing-description">
                Perfect for a simple professional website.
            </p>

            <div class="price">
                <span>From</span>
                1,000 DH
            </div>

            <ul>
                <li><i class="fa-solid fa-check"></i> Responsive website</li>
                <li><i class="fa-solid fa-check"></i> Up to 5 pages</li>
                <li><i class="fa-solid fa-check"></i> Modern design</li>
                <li><i class="fa-solid fa-check"></i> Contact form</li>
                <li><i class="fa-solid fa-check"></i> Basic SEO</li>
            </ul>

            <a href="#contact" class="pricing-btn">
                Get Started
            </a>
        </div>


        <div class="pricing-card featured">

            <div class="popular">
                Most Popular
            </div>

            <div class="pricing-icon">
                <i class="fa-solid fa-laptop-code"></i>
            </div>

            <h3>Business</h3>

            <p class="pricing-description">
                For businesses that need a complete professional website.
            </p>

            <div class="price">
                <span>From</span>
                3,000 DH
            </div>

            <ul>
                <li><i class="fa-solid fa-check"></i> Custom design</li>
                <li><i class="fa-solid fa-check"></i> Responsive website</li>
                <li><i class="fa-solid fa-check"></i> Dynamic features</li>
                <li><i class="fa-solid fa-check"></i> Database integration</li>
                <li><i class="fa-solid fa-check"></i> Admin panel</li>
                <li><i class="fa-solid fa-check"></i> SEO optimization</li>
            </ul>

            <a href="#contact" class="pricing-btn">
                Start Project
            </a>
        </div>


        <div class="pricing-card">

            <div class="pricing-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>

            <h3>E-Commerce</h3>

            <p class="pricing-description">
                Complete online store for selling products online.
            </p>

            <div class="price">
                <span>From</span>
                4,500 DH
            </div>

            <ul>
                <li><i class="fa-solid fa-check"></i> Online store</li>
                <li><i class="fa-solid fa-check"></i> Product management</li>
                <li><i class="fa-solid fa-check"></i> Shopping cart</li>
                <li><i class="fa-solid fa-check"></i> Order management</li>
                <li><i class="fa-solid fa-check"></i> Admin dashboard</li>
                <li><i class="fa-solid fa-check"></i> Responsive design</li>
            </ul>

            <a href="#contact" class="pricing-btn">
                Build My Store
            </a>
        </div>

    </div>
</section>


<!-- ================= FAQ ================= -->
<section class="faq section" id="faq">

    <div class="section-header">
        <span class="section-tag">FAQ</span>
        <h2>Frequently Asked <span>Questions</span></h2>
        <p>
            Some common questions about our services.
        </p>
    </div>

    <div class="faq-container">

        <details>
            <summary>
                How long does a website take to build?
                <i class="fa-solid fa-plus"></i>
            </summary>

            <p>
                The duration depends on the complexity of the project.
                A simple website can take a few days, while larger
                applications may require several weeks.
            </p>
        </details>


        <details>
            <summary>
                Can you create an e-commerce website?
                <i class="fa-solid fa-plus"></i>
            </summary>

            <p>
                Yes. We can build online stores with products,
                categories, shopping carts, orders and administration
                features.
            </p>
        </details>


        <details>
            <summary>
                Do you make responsive websites?
                <i class="fa-solid fa-plus"></i>
            </summary>

            <p>
                Yes. All websites are designed to work on computers,
                tablets and smartphones.
            </p>
        </details>


        <details>
            <summary>
                Can you maintain my existing website?
                <i class="fa-solid fa-plus"></i>
            </summary>

            <p>
                Yes. We can fix bugs, improve performance, add features
                and maintain existing websites.
            </p>
        </details>


        <details>
            <summary>
                Can I request a custom project?
                <i class="fa-solid fa-plus"></i>
            </summary>

            <p>
                Absolutely. Contact us with your requirements and we can
                create a solution adapted to your specific needs.
            </p>
        </details>

    </div>

</section>

<!-- ================= CONTACT ================= -->

<section class="contact section" id="contact">

    <div class="section-header">
        <span class="section-tag">Contact</span>
        <h2>Let's <span>Work Together</span></h2>
        <p>
            Have a project in mind? Send us a message and let's discuss it.
        </p>
    </div>

    <div class="contact-container">

        <div class="contact-info">

            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <div>
                    <h3>Email</h3>
                    <p>technologiesvts@gmail.com</p>
                </div>
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <h3>Location</h3>
                    <p>Morocco</p>
                </div>
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <h3>Availability</h3>
                    <p>Monday - Friday</p>
                </div>
            </div>

        </div>

  <br>
  <?php if ($success !== ""): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if ($error !== ""): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<script>
    setTimeout(function () {
        document.querySelectorAll('.alert-success, .alert-danger')
            .forEach(function (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';

                setTimeout(function () {
                    alert.remove();
                }, 500);
            });
    }, 5000);
</script>
       <form action="index.php#contact" method="POST">

            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Your name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="your@email.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input
                    type="text"
                    id="subject"
                    name="subject"
                    placeholder="Project subject"
                    required
                >
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea
                    id="message"
                    name="message"
                    rows="6"
                    placeholder="Tell us about your project..."
                    required
                ></textarea>
            </div>

            <button type="submit" class="btn" name="send_message">
                <i class="fa-solid fa-paper-plane"></i>
                Send Message
            </button>

        </form>

    </div>

</section>

</main>


<!-- ================= FOOTER ================= -->

<footer class="footer">

    <div class="footer-main">

        <div class="footer-brand">

            <a href="#home" class="logo">
                <span>VTS</span>
                <small>Technologies</small>
            </a>

            <p>
                Digital & Software Solutions.
                We build modern digital experiences
                for ambitious businesses.
            </p>

            <div class="social-links">

                <a href="https://www.linkedin.com/in/achraf-iaazza-8469b6317/?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3ByEBtfaJWQ6efL%2BGvKTEyTQ%3D%3D" aria-label="LinkedIn">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>

                <a href="https://github.com/achrafachrafe" aria-label="GitHub">
                    <i class="fa-brands fa-github"></i>
                </a>

                <a href="https://www.instagram.com/vts.technologies?stkn=eGtuYWVpbW5nZnlt" aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <a href="https://www.facebook.com/share/17eXpy2ZBw/" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
 
                 <a href="https://www.tiktok.com/@vts.technologies?_r=1&_t=ZS-9AHSCaaaMTM" aria-label="tikTok">
                    <i class="fa-brands fa-tiktok"></i>
                </a>

                <a href="https://www.youtube.com/@VTSTechnologies" aria-label="Youtube">
                    <i class="fa-brands fa-youtube"></i>
                </a>
                
            </div>

        </div>


        <div class="footer-column">

            <h4>Company</h4>

            <a href="#about">About</a>
            <a href="#services">Services</a>
            <a href="#projects">Projects</a>
            <a href="#contact">Contact</a>

        </div>


        <div class="footer-column">

            <h4>Services</h4>

            <a href="#services">Web Development</a>
            <a href="#services">E-Commerce</a>
            <a href="#services">Web Applications</a>
            <a href="#services">UI/UX Design</a>
            <a href="#services">Custom Software</a>
            <a href="#services">Maintenance & Support</a>
            <a href="#services">Mobile App Development</a>
            <a href="#services">SEO & Digital Marketing</a>
            <a href="#services">Graphic Design & Branding</a>

        </div>


     

    </div>


    <div class="footer-bottom">

        <p>
            © 2026 VTS Technologies. All rights reserved.
        </p>

        <p>
            Made with <i class="fa-solid fa-code"></i> by VTS
        </p>

    </div>

</footer>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("seoMenuToggle");
    const nav = document.getElementById("seoNav");

    if (!menuBtn || !nav) {
        console.error("Menu button or navigation not found.");
        return;
    }

    function closeMenu() {
        nav.classList.remove("show");
        menuBtn.setAttribute("aria-expanded", "false");
        menuBtn.setAttribute("aria-label", "Ouvrir le menu");
        menuBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
    }

    menuBtn.addEventListener("click", function () {
        const isOpen = nav.classList.toggle("show");

        menuBtn.setAttribute("aria-expanded", String(isOpen));
        menuBtn.setAttribute(
            "aria-label",
            isOpen ? "Fermer le menu" : "Ouvrir le menu"
        );

        menuBtn.innerHTML = isOpen
            ? '<i class="fa-solid fa-xmark"></i>'
            : '<i class="fa-solid fa-bars"></i>';
    });

    nav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", closeMenu);
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 900) {
            closeMenu();
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const themeBtn = document.getElementById("themeBtn");
    const icon = themeBtn?.querySelector("i");

    if (!themeBtn || !icon) return;

    function applyTheme(theme) {
        document.body.classList.toggle("light-mode", theme === "light");

        icon.className = theme === "light"
            ? "fa-solid fa-sun"
            : "fa-solid fa-moon";

        themeBtn.setAttribute(
            "aria-label",
            theme === "light" ? "Activer le mode sombre" : "Activer le mode clair"
        );

        localStorage.setItem("vts-theme", theme);
    }

    applyTheme(localStorage.getItem("vts-theme") || "light");

    themeBtn.addEventListener("click", function () {
        const isLight = document.body.classList.contains("light-mode");
        applyTheme(isLight ? "dark" : "light");
    });
});
</script>
<script>
const sections = document.querySelectorAll("section[id]");
const navLinks = document.querySelectorAll("#seoNav a");

window.addEventListener("scroll", () => {
    let current = "";

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;

        if (window.scrollY >= sectionTop) {
            current = section.getAttribute("id");
        }
    });

    navLinks.forEach(link => {
        link.classList.remove("active");

        if (link.getAttribute("href") === "#" + current) {
            link.classList.add("active");
        }
    });
});
</script>
</body>
</html>