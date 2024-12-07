<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="../../css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="../../css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="../../css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="../../css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="../../css/fa-brands.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="/home"><img alt="logo"
                                                                                   class="d-inline-block align-top mr-2"
                                                                                   src="../../img/logo.png"> Учебный
        проект</a>
    <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
            data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/home">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/show_login">Войти</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/exit_login">Выйти</a>
            </li>
        </ul>
    </div>
</nav>
<?= $this->section('content') ?>

<footer class="page-footer" role="contentinfo">
    <div class="d-flex align-items-center flex-1 text-muted">
        <span class="hidden-md-down fw-700">2020 © Учебный проект</span>
    </div>
    <div>
        <ul class="list-table m-0">
            <li><a href="intel_introduction.html" class="text-secondary fw-700">Home</a></li>
            <li class="pl-3"><a href="info_app_licensing.html" class="text-secondary fw-700">About</a></li>
        </ul>
    </div>
</footer>

</body>
</html>
