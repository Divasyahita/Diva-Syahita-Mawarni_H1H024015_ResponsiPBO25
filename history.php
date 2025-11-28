<?php
require_once 'pokemon.php';

date_default_timezone_set('Asia/Jakarta'); // Perbaikan zona waktu WIB

$historyFile = __DIR__ . '/data/history.json';
$historyJson = @file_get_contents($historyFile);
$history = $historyJson ? json_decode($historyJson, true) : [];
if (!is_array($history)) $history = [];

$history = array_reverse($history);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>PRTC — Riwayat Latihan</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Trebuchet MS", sans-serif;
            color: #fff;
            background: url('https://i.ibb.co.com/dwJ8CnK7/background.jpg') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(2px);
        }

        .card {
            width: 900px;
            margin: 50px auto;
            padding: 25px;
            background: rgba(0, 0, 0, 0.65);
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 255, 120, 0.35);
        }

        h1 {
            text-align: center;
            text-shadow: 0 0 12px #00ff90;
            margin-bottom: 25px;
        }

        .nav {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 5px;
            background: #00c96b;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.2s;
            box-shadow: 0 0 10px #00ff90;
        }

        .btn:hover {
            background: #00ff90;
            color: #000;
        }

        .btn.danger {
            background: #ff5f5f;
            box-shadow: 0 0 10px #ff8a8a;
        }

        .btn.danger:hover {
            background: #ff8080;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: rgba(0, 255, 140, 0.25);
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        tr:hover {
            background: rgba(0, 255, 120, 0.15);
        }

        th {
            text-shadow: 0 0 5px #00ff90;
        }
    </style>

</head>
<body>

<div class="card">
    <h1>Riwayat Latihan — Bulbasaur</h1>

    <div class="nav">
        <a class="btn" href="index.php">Beranda</a>
        <a class="btn" href="train.php">Mulai Latihan</a>
    </div>

    <?php if (empty($history)): ?>
        <p style="text-align:center;">Tidak ada sesi latihan tersimpan.</p>

    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Waktu (WIB)</th>
                    <th>Jenis</th>
                    <th>Intensitas</th>
                    <th>Level</th>
                    <th>HP</th>
                    <th>Catatan</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($history as $h): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars(date('d M Y, H:i:s', strtotime($h['timestamp']))); ?>
                    </td>
                    <td><?php echo htmlspecialchars($h['jenis']); ?></td>
                    <td><?php echo htmlspecialchars($h['intensity']); ?></td>
                    <td><?php echo htmlspecialchars($h['oldLevel']) . ' → ' . htmlspecialchars($h['newLevel']); ?></td>
                    <td><?php echo htmlspecialchars($h['oldHP']) . ' → ' . htmlspecialchars($h['newHP']); ?></td>
                    <td><?php echo htmlspecialchars($h['note']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <form method="post" action="history.php" style="margin-top:12px; text-align:center;">
        <button name="clear" value="1" class="btn danger">Hapus Riwayat</button>
    </form>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    file_put_contents($historyFile, json_encode([]));
    header("Location: history.php");
    exit;
}
?>

</div>

</body>
</html>
