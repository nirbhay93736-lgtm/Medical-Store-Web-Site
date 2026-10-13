<?php
require __DIR__ . '/config.php';
try {
    $pdo = db();
    $stats = [
        'medicines' => (int)$pdo->query('SELECT COUNT(*) FROM medicines WHERE active=1')->fetchColumn(),
        'suppliers' => (int)$pdo->query('SELECT COUNT(*) FROM suppliers WHERE active=1')->fetchColumn(),
        'today' => (float)$pdo->query('SELECT COALESCE(SUM(total),0) FROM sales WHERE DATE(created_at)=CURDATE()')->fetchColumn(),
        'low' => (int)$pdo->query('SELECT COUNT(*) FROM medicines WHERE active=1 AND stock_qty <= reorder_level')->fetchColumn(),
    ];
    $recent = $pdo->query('SELECT id, invoice_no, customer_name, total, created_at FROM sales ORDER BY id DESC LIMIT 6')->fetchAll();
    $lowStock = $pdo->query('SELECT name, stock_qty, unit FROM medicines WHERE active=1 AND stock_qty <= reorder_level ORDER BY stock_qty ASC LIMIT 5')->fetchAll();
} catch (Throwable $e) { $setupError = true; }
$pageTitle = 'Overview'; require __DIR__ . '/partials/header.php';
?>
<?php if (!empty($setupError)): ?><section class="panel setup"><div class="eyebrow">DATABASE CONNECTION</div><h2>Let’s get your store online</h2><p>Import <code>database.sql</code> into MySQL, then set your database login in <code>config.php</code>.</p><a class="button" href="README.md">Read setup guide</a></section><?php else: ?>
<div class="welcome"><div><div class="eyebrow">TUESDAY, <?= h(date('d M Y')) ?></div><h1>Good day, store team <span>✦</span></h1><p>Your medical store at a glance.</p></div><a class="button" href="billing.php">＋ New bill</a></div>
<div class="stats-grid"><article class="stat-card"><div class="stat-icon lilac">▦</div><div class="stat-label">Active medicines</div><strong><?= $stats['medicines'] ?></strong><small>In your catalogue</small></article><article class="stat-card"><div class="stat-icon mint">₹</div><div class="stat-label">Sales today</div><strong><?= money($stats['today']) ?></strong><small>Across all invoices</small></article><article class="stat-card"><div class="stat-icon peach">♙</div><div class="stat-label">Suppliers</div><strong><?= $stats['suppliers'] ?></strong><small>Active partners</small></article><article class="stat-card"><div class="stat-icon rose">!</div><div class="stat-label">Low stock</div><strong><?= $stats['low'] ?></strong><small>At or below reorder level</small></article></div>
<div class="content-grid"><section class="panel"><div class="panel-heading"><div><div class="eyebrow">ACTIVITY</div><h2>Recent bills</h2></div><a class="text-link" href="billing.php">Open billing →</a></div><div class="table-wrap"><table><thead><tr><th>Invoice</th><th>Customer</th><th>Date</th><th class="right">Amount</th></tr></thead><tbody><?php if (!$recent): ?><tr><td colspan="4" class="empty">No bills yet. Create your first invoice.</td></tr><?php endif; ?><?php foreach ($recent as $row): ?><tr><td><a class="invoice-link" href="invoice.php?id=<?= (int)$row['id'] ?>">#<?= h($row['invoice_no']) ?></a></td><td><?= h($row['customer_name'] ?: 'Walk-in customer') ?></td><td><?= h(date('d M, h:i A', strtotime($row['created_at']))) ?></td><td class="right amount"><?= money($row['total']) ?></td></tr><?php endforeach; ?></tbody></table></div></section><section class="panel stock-panel"><div class="panel-heading"><div><div class="eyebrow">INVENTORY WATCH</div><h2>Restock soon</h2></div><a class="text-link" href="medicines.php">Inventory →</a></div><?php if (!$lowStock): ?><div class="empty">Everything is looking stocked.</div><?php endif; ?><?php foreach ($lowStock as $item): ?><div class="stock-row"><div class="pill-icon">✚</div><div class="stock-name"><b><?= h($item['name']) ?></b><span>Reorder recommended</span></div><span class="stock-count"><?= h($item['stock_qty']) ?> <?= h($item['unit']) ?></span></div><?php endforeach; ?><div class="quick-actions"><a href="medicines.php">＋ Add medicine</a><a href="suppliers.php">＋ Add supplier</a></div></section></div>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
