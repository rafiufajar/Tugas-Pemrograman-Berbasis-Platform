<?php
/**
 * CARA MENJALANKAN:
 * 1. Buka PowerShell/Terminal
 * 2. Ketik: php -S localhost:8000
 * 3. Buka browser: http://localhost:8000/tugas5.php
 */

// Koneksi ke database
$host = "localhost";
$db = "todo_app";
$user = "root";
$pass = "";
$error = null;
$todos = [];

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // DELETE
    if ($_POST['action'] ?? false == 'delete' && $_POST['id'] ?? false) {
        $conn->prepare("DELETE FROM todos WHERE id=?")->execute([$_POST['id']]);
    }
    
    // UPDATE STATUS
    if ($_POST['action'] ?? false == 'toggle' && $_POST['id'] ?? false) {
        $stmt = $conn->prepare("SELECT status FROM todos WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $t = $stmt->fetch();
        if ($t) {
            $new_status = $t['status'] == 'done' ? 'pending' : 'done';
            $conn->prepare("UPDATE todos SET status=? WHERE id=?")->execute([$new_status, $_POST['id']]);
        }
    }
    
    // CREATE
    if ($_POST['action'] ?? false == 'add' && $_POST['title'] ?? false) {
        $title = trim($_POST['title']);
        if ($title) $conn->prepare("INSERT INTO todos (title) VALUES (?)")->execute([$title]);
    }
    
    // READ
    $todos = $conn->query("SELECT * FROM todos ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { color: white; text-align: center; margin-bottom: 30px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .form-box { background: white; padding: 20px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .form-box input { width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; font-size: 1em; margin-bottom: 10px; }
        .form-box input:focus { outline: none; border-color: #667eea; }
        .form-box button { width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1em; font-weight: bold; transition: 0.3s; }
        .form-box button:hover { background: #764ba2; }
        .error { background: #ffebee; border: 2px solid #f44336; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .todo-card { background: white; padding: 20px; border-radius: 12px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #667eea; transition: 0.3s; }
        .todo-card:hover { box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .todo-card.done { opacity: 0.7; border-left-color: #4CAF50; }
        .todo-card.pending { border-left-color: #FF9800; }
        .todo-info { flex: 1; }
        .todo-title { font-size: 1.1em; color: #333; font-weight: 600; }
        .todo-card.done .todo-title { text-decoration: line-through; color: #999; }
        .todo-time { font-size: 0.85em; color: #999; margin-top: 5px; }
        .todo-status { background: #4CAF50; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.8em; margin-right: 10px; }
        .todo-card.pending .todo-status { background: #FF9800; }
        .btn-group { display: flex; gap: 8px; }
        button { padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9em; font-weight: bold; transition: 0.3s; }
        .btn-toggle { background: #FF9800; color: white; }
        .btn-toggle:hover { background: #F57C00; }
        .btn-delete { background: #f44336; color: white; }
        .btn-delete:hover { background: #d32f2f; }
        .empty { text-align: center; color: white; padding: 60px 20px; }
    </style>
</head>
<body>
<div class="container">
    <h1>📝 My Todo List</h1>
    
    <?php if ($error): ?>
        <div class="error">❌ Error: <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div class="form-box">
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="title" placeholder="Tambah todo baru..." required>
            <button type="submit">➕ Tambah Todo</button>
        </form>
    </div>
    
    <?php if (count($todos) > 0): ?>
        <?php foreach ($todos as $t): ?>
            <div class="todo-card <?= $t['status'] ?>">
                <div class="todo-info">
                    <div class="todo-title"><?= htmlspecialchars($t['title']) ?></div>
                    <div class="todo-time">🕐 <?= date('d-m-Y H:i', strtotime($t['created_at'])) ?></div>
                </div>
                <div class="btn-group">
                    <span class="todo-status"><?= ucfirst($t['status']) ?></span>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                        <button type="submit" class="btn-toggle"><?= $t['status'] == 'done' ? '↩️ Pending' : '✓ Done' ?></button>
                    </form>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus todo ini?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                        <button type="submit" class="btn-delete">🗑️ Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty">
            <div style="font-size: 3em; margin-bottom: 20px;">📭</div>
            <p>Belum ada todo. Mulai buat rencana Anda sekarang!</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>