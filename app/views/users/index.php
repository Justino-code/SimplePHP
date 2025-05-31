<div class="container mt-4">
    <h1 class="mb-4">👥 Lista de Usuários</h1>

    <?php if (!empty($users)): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($users as $user): ?>
                <div class="col">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($user['nome']) ?></h5>
                            <p class="card-text mb-1"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                            <p class="card-text"><strong>Função:</strong> <?= htmlspecialchars($user->role ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-3">Nenhum usuário encontrado.</div>
    <?php endif; ?>
</div>
