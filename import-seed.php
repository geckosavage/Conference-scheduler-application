<?php
$pdo = new PDO('sqlite:var/data.db');
$sql = file_get_contents('data/seed.sql');
$pdo->exec($sql);
echo "seeded\n";
