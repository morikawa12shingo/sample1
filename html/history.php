<!DOCTYPE html>
<html>
<head>
    <title>地下チンチロ - 戦績</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color:rgb(169, 206, 255);
        }
    </style>
</head>
<body>

<h1>地下チンチロ - 戦績</h1>

<?php

//日付を東京に設定する。
date_default_timezone_set("Asia/Tokyo");

// データベース接続情報
$servername = "mysql"; 
$username = "root"; 
$password = "password"; 
$dbname = "sample1"; 

try {
    $pdo = new PDO("mysql:host=$servername:3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    // PDOエラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // chinchiro_historyテーブルからデータを取得
    $sql = "SELECT id, dice_result, yaku, created_at FROM chinchiro_history ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>出目</th><th>役</th><th>チンチロ日時</th></tr></thead>";
        echo "<tbody>";
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['dice_result'] . "</td>";
            echo "<td>" . $row['yaku'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>まだ戦績がありません。</p>";
    }

} catch(PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
}

$pdo = null; // データベース接続を閉じる
?>

<p><a href="index.php">戻る</a></p>

</body>
</html>