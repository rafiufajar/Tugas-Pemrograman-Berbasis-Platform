<?php
$host = "localhost";
$db   = "todo_app";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Koneksi berhasil!\n\n";
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

echo "== DAFTAR TODO ==\n";
$stmt = $conn->query("SELECT * FROM todos");
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($todos as $t) {
    echo "ID: {$t['id']} | Title: {$t['title']} | Status: {$t['status']}\n";
}

echo "\n";

echo "Masukkan ID todo yang ingin diupdate: ";
$id = trim(fgets(STDIN));

echo "Masukkan status baru (pending/done): ";
$statusBaru = trim(fgets(STDIN));

if ($statusBaru === 'pending' || $statusBaru === 'done') {
    $stmt = $conn->prepare("UPDATE todos SET status = ? WHERE id = ?");
    $result = $stmt->execute([$statusBaru, $id]);

    if ($stmt->rowCount() > 0) {
        echo "\nTodo berhasil diupdate!\n";
    } else {
        echo "\nGagal update! ID tidak ditemukan atau data sama.\n";
    }
} else {
    echo "\nStatus tidak valid! Gunakan 'pending' atau 'done'.\n";
}

echo "\n== DAFTAR TODO SETELAH UPDATE ==\n";
$stmt = $conn->query("SELECT * FROM todos");
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($todos as $t) {
    echo "ID: {$t['id']} | Title: {$t['title']} | Status: {$t['status']}\n";
}
?>