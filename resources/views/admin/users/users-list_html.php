<div class="dashboard-grid" style="grid-template-columns: 1fr;">
    <main class="main-content">
        <div class="profile-header">
            <div class="user-meta">
                <h2>Liste des utilisateurs</h2>
            </div>
            <div style="margin-left: auto; margin-top: 0.5rem;">
                <span class="btn btn-outline" style="cursor: default; border-color: var(--vue-green); color: var(--vue-green);">
                    Total: <?= count($users) ?> Utilisateurs
                </span>
            </div>
        </div>

        <div class="card" style="margin: 2rem 0; max-width: none; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid #c0c0c0;">
                        <th style="padding: 1rem;">Pseudo</th>
                        <th style="padding: 1rem;">Email</th>
                        <th style="padding: 1rem;">Rôle</th>
                        <th style="padding: 1rem;">Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr style="border-bottom: 1px solid #c0c0c0;">
                            <td style="padding: 1rem; font-weight: 500;"><?= htmlspecialchars($user['pseudo']) ?></td>
                            <td style="padding: 1rem; color: #64748b;"><?= htmlspecialchars($user['email']) ?></td>
                            <td style="padding: 1rem;">
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; background: <?= $user['role'] === 'admin' ? '#e7fced' : '#efe2f7' ?>; color: <?= $user['role'] === 'admin' ? '#16a34a' : '#9a0feb' ?>; border: 1px solid <?= $user['role'] === 'admin' ? '#22c95f' : '#bf56fc' ?>;">
                                    <?= Role::from($user['role'])->label() ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; color: #94a3b8; font-size: 0.85rem;">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>