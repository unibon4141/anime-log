<?php
// ==========================
// 放送中のアニメ一覧ページ
// ==========================
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <title>放送中のアニメ一覧</title>
</head>
<?php
  include('header.php');
  ?>
  <main id="main">
  <?php
    $api = 'http://api.moemoe.tokyo/anime/v1/master';
    $year = 2021;
    $season = 1;

    $response = file_get_contents("$api/$year/$season");

    $animeList = json_decode($response);

    // $anime オブジェクト
    ?>
    <ul>
    <?php
    foreach($animeList as $anime){
      ?>
      <li>
      <?php
        echo $anime->title."\n";
        ?>
        </li>
        <?php
    }
?>
</ul>
  </main>
<?php
  include('footer.php');
  ?>
