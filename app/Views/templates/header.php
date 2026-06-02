<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Technoliner SAS — Soluciones de empaque seguras y sostenibles para tu industria.">
    <title>Technoliner SAS — Protege lo esencial, preserva la calidad</title>

    <!-- PWA -->
    <meta name="theme-color" content="#0e8a6e">
    <link rel="manifest" href="<?= base_url('manifest.webmanifest') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/icons/favicon-32.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/icons/apple-touch-icon.png') ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Technoliner">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

    <header class="site-header" id="inicio">
        <div class="container header-inner">
            <a href="<?= base_url('/') ?>" class="brand">
                <span class="brand-mark">T</span>
                <span class="brand-text">
                    <strong>Technoliner</strong>
                    <small>Protege lo esencial, preserva la calidad</small>
                </span>
            </a>

            <nav class="main-nav" id="mainNav">
                <a href="#inicio">Inicio</a>
                <a href="#nosotros">Nosotros</a>
                <a href="#productos">Productos</a>
                <a href="#contacto">Contacto</a>
                <a href="#contacto" class="btn btn-primary btn-nav">Contactar</a>
            </nav>

            <button class="nav-toggle" id="navToggle" aria-label="Abrir menú">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
