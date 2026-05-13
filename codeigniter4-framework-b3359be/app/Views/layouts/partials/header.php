<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TechMada RH' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <button class="menu-burger" onclick="toggleSidebar()">☰</button>
            <h1 class="navbar-title">TechMada RH</h1>
        </nav>
    </header>
    <aside class="sidebar">
        <!-- Sidebar dynamique selon le rôle -->
        <?= $this->include('layouts/partials/sidebar') ?>
    </aside>
    <main class="content">
