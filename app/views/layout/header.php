<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Escuela de Natación' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/escuela-natacion/">🏊 Natación Pro</a>
            <div class="navbar-nav">
                <a class="nav-link" href="?url=home">Inicio</a>
                <a class="nav-link" href="?url=alumnos">Alumnos</a>
                <a class="nav-link" href="?url=clases">Clases</a>
            </div>
        </div>
    </nav>
    <main class="container">