<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/style_daftar_belanja.css">
    <link rel="stylesheet" href="/css/style_nav_footer.css">

    <title><?= $title; ?></title>
</head>

<body>
    <?= $this->include('layout/navbar'); ?>

    <?= $this->renderSection('content'); ?>

    <?= $this->include('layout/footer'); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script src="/js/script.js"></script>
    <script src="/js/script_daftar_belanja.js"></script>
</body>

</html>