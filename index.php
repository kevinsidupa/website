<?php
$dbPath = __DIR__ . '/data.sqlite';
$db = new SQLite3($dbPath);
$db->exec("CREATE TABLE IF NOT EXISTS kolam (id INTEGER PRIMARY KEY AUTOINCREMENT, nama TEXT, keterangan TEXT, created_at TEXT)");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    $nama = trim($_POST['nama']);
    $ket = trim($_POST['ket']);
    if ($nama !== '') {
        $stmt = $db->prepare("INSERT INTO kolam (nama, keterangan, created_at) VALUES (:n, :k, :t)");
        $stmt->bindValue(':n', $nama, SQLITE3_TEXT);
        $stmt->bindValue(':k', $ket, SQLITE3_TEXT);
        $stmt->bindValue(':t', date('Y-m-d H:i:s'), SQLITE3_TEXT);
        $stmt->execute();
    }
    header("Location: /");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $db->exec("DELETE FROM kolam WHERE id=$id");
    header("Location: /");
    exit;
}
$data = [];
$res = $db->query("SELECT * FROM kolam ORDER BY id DESC");
while ($row = $res->fetchArray(SQLITE3_ASSOC)) { $data[] = $row; }
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>PAPAWU MEKONGGA</title>
<style>body{background:#0f172a;color:#fff;font-family:sans-serif;margin:0;padding:20px}h1{text-align:center} .box{background:#1e293b;padding:20px;border-radius:15px;margin-bottom:20px}input{width:100%;padding:12px;margin:8px 0;border-radius:10px;border:0;background:#0f172a;color:#fff;box-sizing:border-box}button{width:100%;padding:12px;background:#38bdf8;border:0;border-radius:10px;font-weight:bold;color:#000} .item{padding:10px 0;border-bottom:1px solid #334155;display:flex;justify-content:space-between}</style></head><body>
<h1>PAPAWU MEKONGGA</h1>
<div class="box"><form method="POST"><input name="nama" placeholder="Nama Kolam (ex: A1)" required><input name="ket" placeholder="Keterangan (ex: Pakan 2kg)"><button type="submit">Simpan</button></form></div>
<div class="box"><b>Data: <?=count($data)?></b><?php if(empty($data)) echo "<p style='color:#94a3b8'>Belum ada data</p>"; else foreach($data as $d): ?>
<div class="item"><span><b><?=htmlspecialchars($d['nama'])?></b><br><small style="color:#94a3b8"><?=htmlspecialchars($d['keterangan'])?> - <?=$d['created_at']?></small></span><a href="?hapus=<?=$d['id']?>" style="color:#ef4444;text-decoration:none">Hapus</a></div>
<?php endforeach; ?></div></body></html>
