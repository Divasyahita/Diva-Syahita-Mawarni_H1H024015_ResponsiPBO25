<?php
require_once 'pokemon.php';

$bulbasaur = new Pokemon('Bulbasaur', 'Grass', 5, 45, ['Vine Whip', 'Leech Seed']);

$historyFile = __DIR__ . '/data/history.json';
if (!file_exists(dirname($historyFile))) {
    mkdir(dirname($historyFile), 0777, true);
}
if (!file_exists($historyFile)) {
    file_put_contents($historyFile, json_encode([]));
}

$trainResult = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis'] ?? 'Attack';
    $intensity = $_POST['intensity'] ?? 10;

    $jenis = preg_replace('/[^a-zA-Z]/', '', $jenis);
    $intensity = (int)$intensity;
    if ($intensity < 1) $intensity = 1;

    $historyJson = @file_get_contents($historyFile);
    $history = $historyJson ? json_decode($historyJson, true) : [];
    if (!is_array($history)) $history = [];

    if (count($history) > 0) {
        $last = end($history);
        if (isset($last['newLevel'])) $bulbasaur->level = (int)$last['newLevel'];
        if (isset($last['newHP'])) $bulbasaur->hp = (int)$last['newHP'];
    }

    $trainResult = $bulbasaur->train($jenis, $intensity);

    $entry = [
        'timestamp' => date('c'),
        'jenis' => $trainResult['type'],
        'intensity' => $trainResult['intensity'],
        'oldLevel' => $trainResult['oldLevel'],
        'newLevel' => $trainResult['newLevel'],
        'oldHP' => $trainResult['oldHP'],
        'newHP' => $trainResult['newHP'],
        'note' => $trainResult['note']
    ];

    $history[] = $entry;
    $fp = fopen($historyFile, 'c+');
    if ($fp) {
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($history, JSON_PRETTY_PRINT));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    } else {
        $error = "Gagal menyimpan riwayat latihan. Periksa permission folder data/";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>PRTC — Latihan Bulbasaur</title>

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
            width: 580px;
            margin: 50px auto;
            padding: 25px;
            background: rgba(0, 0, 0, 0.65);
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 255, 100, 0.35);
            text-align: center;
        }

        h1 {
            text-shadow: 0 0 12px #00ff90;
            margin-bottom: 25px;
        }

        form label {
            display: block;
            text-align: left;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
            box-shadow: 0 0 8px rgba(0, 255, 150, 0.3);
        }

        /* === PERBAIKAN WARNA DROPDOWN === */
        select option {
            background: #1e1e1e;
            color: #fff;
            padding: 10px;
        }
        
        select option:hover {
            background: #00ff90;
            color: #000;
        }

        .form-actions {
            margin-top: 20px;
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

        .result, .error {
            margin-top: 25px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            text-align: left;
        }

        .result h2 {
            text-shadow: 0 0 8px #00ff90;
        }

        .muted {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Training Session — Bulbasaur</h1>

    <form method="post">
        <label>Jenis Latihan:</label>
        <select name="jenis">
            <option value="Attack">Attack</option>
            <option value="Defense">Defense</option>
            <option value="Speed">Speed</option>
        </select>

        <label>Intensitas Latihan (angka):</label>
        <input type="number" name="intensity" value="20" min="1">

        <div class="form-actions">
            <button type="submit" class="btn">Mulai Latihan</button>
            <a href="index.php" class="btn">Beranda</a>
            <a href="history.php" class="btn">Riwayat Latihan</a>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="error">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($trainResult): ?>
        <div class="result">
            <h2>Hasil Latihan</h2>

            <p><strong>Jenis:</strong> <?= htmlspecialchars($trainResult['type']); ?></p>
            <p><strong>Intensitas:</strong> <?= htmlspecialchars($trainResult['intensity']); ?></p>

            <p><strong>Level:</strong> 
                <?= $trainResult['oldLevel']; ?> → <?= $trainResult['newLevel']; ?> 
                (+<?= $trainResult['levelGain']; ?>)
            </p>

            <p><strong>HP:</strong> 
                <?= $trainResult['oldHP']; ?> → <?= $trainResult['newHP']; ?> 
                (+<?= $trainResult['hpGain']; ?>)
            </p>

            <h3>Jurus Spesial (specialMove())</h3>
            <p><strong><?= htmlspecialchars($trainResult['specialMove']['name']); ?></strong> — 
               <?= htmlspecialchars($trainResult['specialMove']['desc']); ?></p>

            <p class="muted"><?= htmlspecialchars($trainResult['note']); ?></p>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
