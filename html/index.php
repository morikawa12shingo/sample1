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
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // 3つのサイコロをランダムな数字で振る
    $dice_1 = mt_rand(1, 6);
    $dice_2 = mt_rand(1, 6);
    $dice_3 = mt_rand(1, 6);

    echo "<p>1つ目のサイコロの目は：" . $dice_1 . "...!</p>";
    echo "<p>2つ目のサイコロの目は：" . $dice_2 . "...!</p>";
    echo "<p>3つ目のサイコロの目は：" . $dice_3 . "...!</p>";

    // サイコロの出目を配列に格納
    $dice_deme = [$dice_1, $dice_2, $dice_3];

    sort($dice_deme);

    echo "<p>今回の出目は：";
    foreach ($dice_deme as $dice) {
        echo $dice . " ";
    }
    echo "...！</p>";

    // 役の判定と仲間外れの数字を特定
    $hazure_deme = null; 
    if ($dice_1 == $dice_2 && $dice_1 != $dice_3) {
        $hazure_deme = $dice_3;
    } elseif ($dice_1 == $dice_3 && $dice_1 != $dice_2) {
        $hazure_deme = $dice_2;
    } elseif ($dice_2 == $dice_3 && $dice_2 != $dice_1) {
        $hazure_deme = $dice_1;
    }

    // 役の払い出し
    if ($dice_1 == 1 && $dice_2 == 1 && $dice_3 == 1) {
        echo "<p>ピンゾロ...！圧倒的感謝...！</p>"; 
    } elseif ($dice_1 == $dice_2 && $dice_1 == $dice_3) {
        echo "<p>" . $dice_1 . "のゾロ目...！3倍づけ...！</p>";
    } elseif (($dice_1 == $dice_2 && $dice_1 != $dice_3) || ($dice_1 == $dice_3 && $dice_1 != $dice_2) || ($dice_2 == $dice_3 && $dice_2 != $dice_1)) {
        if ($hazure_deme !== null) {
            echo "<p>出目は" . $hazure_deme . "...！</p>";
        } 
    } elseif (($dice_1 == 1 && $dice_2 == 2 && $dice_3 == 3) || ($dice_1 == 1 && $dice_2 == 3 && $dice_3 == 2) ||($dice_1 == 2 && $dice_2 == 1 && $dice_3 == 3) || ($dice_1 == 2 && $dice_2 == 3 && $dice_3 == 1) ||($dice_1 == 3 && $dice_2 == 1 && $dice_3 == 2) || ($dice_1 == 3 && $dice_2 == 2 && $dice_3 == 1)) {
        echo "<p>ヒフミ...！倍払い...！圧倒的敗北者...！</p>";
    } elseif (($dice_1 == 4 && $dice_2 == 5 && $dice_3 == 6) || ($dice_1 == 4 && $dice_2 == 6 && $dice_3 == 5) ||($dice_1 == 5 && $dice_2 == 4 && $dice_3 == 6) || ($dice_1 == 5 && $dice_2 == 6 && $dice_3 == 4) ||($dice_1 == 6 && $dice_2 == 4 && $dice_3 == 5) || ($dice_1 == 6 && $dice_2 == 5 && $dice_3 == 4)) {
        echo "<p>シゴロ...！2倍づけ...！</p>";
    } else {
        echo "<p>目無し...！1倍払い...！</p>";
    }
}
?>

</body>
</html>