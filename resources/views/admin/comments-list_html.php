<div class="dashboard-grid" style="grid-template-columns: 1fr;">
    <main class="main-content">
        <div class="profile-header">
            <div class="user-meta">
                <h2>Liste des Commentaires</h2>
            </div>
            <div style="margin-left: auto; margin-top: 0.5rem;">
                <span class="btn btn-outline" style="cursor: default; border-color: var(--vue-green); color: var(--vue-green);">
                    Total: <?= count($comments) ?> commentaires
                </span>
            </div>
        </div>

        <div class="card" style="margin: 2rem 0; max-width: none; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid #c0c0c0;">
                        <th style="padding: 1rem;">contenu</th>
                        <th style="padding: 1rem;">Date d'édition</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr style="border-bottom: 1px solid #c0c0c0;">
                            <td style="padding: 1rem; font-weight: 500;"><?= $comment['content'] ?></td>
                            <td style="padding: 1rem; color: #94a3b8; font-size: 0.85rem;">
                                <?= date('d/m/Y', strtotime($comment['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>