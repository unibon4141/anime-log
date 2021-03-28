<?php
// ==========================
// 今期のアニメ一覧ページ(トップページ)
// ==========================
include('function.php');


$loginFlg = isLogin();



$year = date('Y');
$month = date('n');
$shown_season = '';
$season;
debug($year.'      '.$month);
if($month >= 1 && $month <4){
  $season = 1;
  $shown_season = '冬';
} else if($month >= 4 && $month < 7){
  $season = 2;
  $shown_season = '春';
}else if($month >= 7 && $month < 10){
  $season = 3;
  $shown_season = '夏';
} else {
  $season = 4;
  $shown_season = '秋';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/body.css">
  <link rel="stylesheet" href="css/footer.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <title>今期のアニメ一覧</title>
</head>
<?php
  include('header.php');
  ?>
  <main id="main">
    <div class="container">
    <h1><?php echo $year.'年'.$shown_season.'アニメ一覧'; ?></h1>
  <?php
    $api = 'http://api.moemoe.tokyo/anime/v1/master';

    $response = file_get_contents("$api/$year/$season");

    $animeList = json_decode($response);
    ?>
    <ul class="anime-list">
    <?php
    foreach($animeList as $anime){
      ?>
      <li class="anime">
      <p class="anime-title">
      <?php
        echo sanitize($anime->title)."\n";
        ?>
        </p>
        
        <button class="<?php if(isLogin()) echo 'myanime-add'; ?>" data-title = "<?php echo $anime->title; ?>">
マイアニメに登録する</button>
        </li>
        <?php
    }
?>
</ul>
</div>
  </main>
<?php
  include('footer.php');
  ?>
