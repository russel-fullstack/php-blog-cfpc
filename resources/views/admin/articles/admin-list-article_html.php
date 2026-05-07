

<h1>Nos articles</h1>
<p>Il y a <?= count($articles); ?> articles</p>

<!-- Affichage des erreurs et succès -->
<?php

if (! empty($errors)) { ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) { ?>
            <p><?= $error ?></p>
        <?php } ?>
    </div>
<?php } ?>

<?php if (isset($success['update'])) { ?>
    <div class="alert alert-success">
        <p><?= $success['update'] ?></p>
    </div>
<?php } ?>
<form method="POST" action="">
    <input type="text" name="search" placeholder="Rechercher un article..." value="<?= htmlspecialchars($searchTerm) ?>">
    <button type="submit">Rechercher</button>

    <a class="button" href="admin-list-article.php">Réinitialiser</a>
    <a class="button" href="admin-add-article.php">Add Article</a>
</form>


<table class="article-table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Titre</th>
            <th>Introduction</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article) { ?>
            <tr>
                <td>
                    <?php if (! empty($article['image'])) { ?>
                        <img src="<?= $article['image'] ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                    <?php } ?>
                </td>
                <td><?= htmlspecialchars($article['title']) ?></td>
                <td><?= htmlspecialchars($article['introduction']) ?></td>
                <td><?= htmlspecialchars($article['created_at']) ?></td>
                <td style="display: flex; justify-content: center; align-items: center;">
                    <a href="article.php?id=<?= urlencode($article['id']); ?>">
                        <i class='bx bx-show'></i>Show
                    </a>
                    <a href="admin-update-article.php?id=<?= urlencode($article['id']); ?>">
                        <i class='bx bx-edit'></i>Edit
                    </a>
                    <a href="admin-delete-article.php?id=<?= urlencode($article['id']); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?!')">
                        <i class='bx bx-trash'></i>Del
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>