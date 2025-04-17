<!DOCTYPE html>
<html>
<head>
    <title>地下チンチロ</title>
</head>
<body>

<h1>地下チンチロ</h1>

<form method="GET">
    <input type="submit" value="運否天賦">
</form>

<?php

// エラーを出力する
ini_set('display_errors', "On");

//日付を東京に設定する。
date_default_timezone_set("Asia/Tokyo");

//データベース接続情報
$servername = "mysql"; //データベースサーバー名
$username = "root"; //データベースユーザー名
$password = "password"; //データベースパスワード
$dbname = "sample1"; //データベース名

try {
    $pdo = new PDO("mysql:host=$servername:3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    //PDOエラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "データベース接続に失敗しました: " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //3つのサイコロをランダムな数字で振る
    $dice_1 = mt_rand(1, 6);
    $dice_2 = mt_rand(1, 6);
    $dice_3 = mt_rand(1, 6);

    echo "<p>1つ目のサイコロの目は：" . $dice_1 . "...!</p>";
    echo "<p>2つ目のサイコロの目は：" . $dice_2 . "...!</p>";
    echo "<p>3つ目のサイコロの目は：" . $dice_3 . "...!</p>";

    //サイコロの出目を配列に格納
    $dice_deme = [$dice_1, $dice_2, $dice_3];
    sort($dice_deme);
    $dice_result = implode(",", $dice_deme); //DB格納用にカンマ区切り文字列に

    echo "<p>今回の出目は：";
    foreach ($dice_deme as $dice) {
        echo $dice . " ";
    }
    echo "...！</p>";

    //役の判定と仲間外れの数字を特定
    $hazure_deme = null;
    $yaku = "目無し"; //デフォルトは目無し
    $payoff = "1倍";

    if ($dice_1 == $dice_2 && $dice_1 != $dice_3) {
        $hazure_deme = $dice_3;
    } elseif ($dice_1 == $dice_3 && $dice_1 != $dice_2) {
        $hazure_deme = $dice_2;
    } elseif ($dice_2 == $dice_3 && $dice_2 != $dice_1) {
        $hazure_deme = $dice_1;
    }

    //役の払い出し
    if ($dice_1 == 1 && $dice_2 == 1 && $dice_3 == 1) {
        echo "<p>ピンゾロ...！圧倒的感謝...！</p>";
        $yaku = "ピンゾロ";
        $payoff = "5倍づけ";
    } elseif ($dice_1 == $dice_2 && $dice_1 == $dice_3) {
        echo "<p>" . $dice_1 . "のゾロ目...！3倍づけ...！</p>";
        $yaku = $dice_1 . "ゾロ";
        $payoff = "3倍づけ";
    } elseif (
        ($dice_1 == $dice_2 && $dice_1 != $dice_3) || 
        ($dice_1 == $dice_3 && $dice_1 != $dice_2) || 
        ($dice_2 == $dice_3 && $dice_2 != $dice_1)) 
        {
        if ($hazure_deme !== null) {
            echo "<p>出目は" . $hazure_deme . "...！</p>";
            $yaku = $hazure_deme;
            $payoff = "1倍づけ";
        }
    } elseif (
        ($dice_1 == 1 && $dice_2 == 2 && $dice_3 == 3) || 
        ($dice_1 == 1 && $dice_2 == 3 && $dice_3 == 2) ||
        ($dice_1 == 2 && $dice_2 == 1 && $dice_3 == 3) || 
        ($dice_1 == 2 && $dice_2 == 3 && $dice_3 == 1) ||
        ($dice_1 == 3 && $dice_2 == 1 && $dice_3 == 2) || 
        ($dice_1 == 3 && $dice_2 == 2 && $dice_3 == 1)) {
        echo "<p>ヒフミ...！倍払い...！圧倒的敗北者...！</p>";
        $yaku = "ヒフミ";
        $payoff = "2倍づけ";
    } elseif (
        ($dice_1 == 4 && $dice_2 == 5 && $dice_3 == 6) || 
        ($dice_1 == 4 && $dice_2 == 6 && $dice_3 == 5) ||
        ($dice_1 == 5 && $dice_2 == 4 && $dice_3 == 6) || 
        ($dice_1 == 5 && $dice_2 == 6 && $dice_3 == 4) ||
        ($dice_1 == 6 && $dice_2 == 4 && $dice_3 == 5) || 
        ($dice_1 == 6 && $dice_2 == 5 && $dice_3 == 4)) {
        echo "<p>シゴロ...！2倍づけ...！</p>";
        $yaku = "シゴロ";
        $payoff = "2倍づけ";
    } else {
        echo "<p>目無し...！1倍払い...！</p>";
    }

    //現在日時を取得
    $created_at = date("Y-m-d H:i:s");

    //chinchiro_historyテーブルにデータを挿入
    $sql = "INSERT INTO chinchiro_history (dice_result, yaku, created_at) VALUES (:dice_result, :yaku, :created_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dice_result', $dice_result);
    $stmt->bindParam(':yaku', $yaku);
    $stmt->bindParam(':created_at', $created_at);

    try {
        $stmt->execute();
        echo "<p>結果を記録しました。</p>";
    } catch(PDOException $e) {
        echo "<p>結果の記録に失敗しました: " . $e->getMessage() . "</p>";
    }
}

$pdo = null; //データベース接続を閉じる
?>

<hr>
<h2>過去の戦績</h2>
<p><a href="history.php">戦績一覧を見る</a></p>

</body>
</html>