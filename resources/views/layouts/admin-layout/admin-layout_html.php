<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogPHP <?= $pageTitle ?></title>
    <link rel="stylesheet" href="resources/css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php include 'admin-sidebar_html.php'; ?>
    <!-- SIDEBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <?php include 'admin-navbar_html.php'; ?>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <?= $pageContent ?>
            <!-- MAIN -->
        </main>
    </section>

    <?php include 'admin-footer_html.php' ?>
    <script src="/resources/js/script.js"></script>
</body>

</html>