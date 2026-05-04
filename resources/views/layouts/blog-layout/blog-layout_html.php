<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogPHP2026 -<?= $pageTitle ?></title>
    <link rel="stylesheet" href="/resources/css/footer.css">
    <link rel="stylesheet" href="/resources/css/blog.css">
    <link rel="stylesheet" href="/resources/css/forms.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include 'blog-header_html.php'; ?>

    <?php if ($flash = flash_get()): ?>
      <div style="background-color: <?= $flash['type'] === 'success' ? '#d4edda' : '#f8d7da' ?>; 
                  color: <?= $flash['type'] === 'success' ? '#155724' : '#721c24' ?>; 
                  padding: 8px; 
                  text-align: center; 
                  border: 1px solid <?= $flash['type'] === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;
                  margin: 7px auto;
                  max-width: 1000px;
                  border-radius: 5px;">
          <?= htmlspecialchars($flash['message']) ?>
      </div>
  <?php endif; ?>
    <main>
        <?= $pageContent ?>
    </main>
    <?php include 'blog-footer_html.php'; ?>
</body>

</html>