<?php
require_once 'pokemon.php';

$bulbasaur = new Pokemon('Bulbasaur', 'Grass', 5, 45, ['Vine Whip', 'Leech Seed']);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>PRTC — Beranda Trainer</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Trebuchet MS", sans-serif;
            color: #fff;

            /* Background gelap bergaya game */
            background: url('https://i.ibb.co.com/dwJ8CnK7/background.jpg') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(2px);
        }

        .card {
            width: 520px;
            margin: 60px auto;
            padding: 25px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 255, 100, 0.3);
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 28px;
            text-shadow: 0 0 10px #00ff90;
        }

        .pokemon-img {
            width: 180px;
            margin-top: 10px;
            filter: drop-shadow(0 0 10px #00ff90);
        }

        ul {
            list-style: none;
            padding: 0;
        }

        .nav {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 4px;
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

        .note {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
        }
    </style>
</head>

<body>
<div class="card">
    <h1>PRTC — Pokémon Assigned</h1>


    <img src="https://i.ibb.co.com/rGczS1Gd/bulbasaur.png" alt="Bulbasaur" class="pokemon-img">

    <h2><?php echo htmlspecialchars($bulbasaur->name); ?></h2>

    <ul>
        <li><strong>Tipe:</strong> <?php echo htmlspecialchars($bulbasaur->type); ?></li>
        <li><strong>Level Awal:</strong> <?php echo htmlspecialchars($bulbasaur->level); ?></li>
        <li><strong>HP Awal:</strong> <?php echo htmlspecialchars($bulbasaur->hp); ?></li>
        <li><strong>Jurus Spesial:</strong> <?php echo htmlspecialchars($bulbasaur->specialMove()['name']); ?></li>
    </ul>

    <div class="nav">
        <a class="btn" href="train.php">Mulai Latihan</a>
        <a class="btn" href="history.php">Riwayat Latihan</a>
    </div>

    <div class="note">
        <h3>Catatan singkat — Pengaruh tipe Pokémon</h3>
        <ul>
            <li><strong>Grass:</strong> unggul pada regenerasi & defense.</li>
            <li><strong>Fire:</strong> kuat pada Attack & Speed.</li>
            <li><strong>Water:</strong> seimbang antara HP & Defense.</li>
            <li><strong>Electric:</strong> sangat cepat & lincah.</li>
        </ul>
    </div>

</div>
</body>
</html>
