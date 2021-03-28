<?php
// ==========================
// アニメ検索ページ
// ==========================
include('function.php');
debug('アニメ検索ページ');
$current_year = date('Y');
$oldest_year = 2014;

if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST内容：'.print_r($_POST, true));
  $api = 'http://api.moemoe.tokyo/anime/v1/master';
  $year = $_POST['year'];
  $season = $_POST['season'];
  $response = file_get_contents("$api/$year/$season");

  $animeList = json_decode($response);

  debug('アニメ検索結果：'.print_r($animeList, true));
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/body.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/search.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <title>アニメ検索</title>
</head>
<?php
  include('header.php');
  ?>
  <main id="main">
    <div class="container">
  <h1>アニメを探す</h1>
  <form action="" method="post">
    <select name="year" id="">
      <?php
        for($i = $oldest_year; $i <= $current_year;$i++){
          ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php
        }
      ?>
    </select>
    <select name="season" id="">
        <option value="2">春アニメ</option>
        <option value="3">夏アニメ</option>
        <option value="4">秋アニメ</option>
        <option value="1">冬アニメ</option>
    </select>
    <input type="submit" value="検索">
  </form>
  <?php 
  if(!empty($animeList)){
    ?>
    <h2>検索結果</h2>
    <ul class="anime-list">
    <?php
    foreach($animeList as $anime){
      ?>
      <li class="anime">
        <p class="anime-title"><?php echo $anime->title; ?></p>
        <button class="add-button <?php if(isLogin()) echo 'myanime-add'; ?>" data-title = "<?php echo sanitize($anime->title); ?>">
マイアニメに登録する</button>
      </li>
      <?php
    }
    ?>
    </ul>
    <?php
  }
  ?>
  </div>
  </main>
<?php
  include('footer.php');
  ?>