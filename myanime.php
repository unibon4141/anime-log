<?php
// =========================
// マイアニメページ
// ==========================
include('function.php');
debug('マイアニメページ');
// ログイン認証
include('auth.php');
$user_id = "";
$myanime_list = array();
if (!empty($_SESSION['login_time'])) {
  $user_id = $_SESSION['user_id'];
}

if (!empty($_POST)) {
  debug('POST送信があります。');
  debug('POST内容：' . print_r($_POST, true));

  if ($_POST['form-type'] === 'status') {
    $anime_id = $_POST['anime-id'];
    $anime_status = $_POST['watch-status'];

    try {
      // DB接続
      $dbh = dbConnect();
      $sql = 'UPDATE anime SET status = :status WHERE id = :id AND delete_flg = 0 ';
      $data = array(
        array(':id', $anime_id, PDO::PARAM_INT),
        array(':status', $anime_status, PDO::PARAM_INT)
      );
      $stmt = queryPost($dbh, $sql, $data);
    } catch (PDOException $e) {
      debug('エラー発生：' . $e->getMessage());
    }
  } else if ($_POST['form-type'] === 'delete') {
    $anime_id = $_POST['anime-id'];
    debug($anime_id);
    try {
      // DB接続
      $dbh = dbConnect();
      $sql = 'UPDATE anime SET delete_flg = 1 WHERE id = :id AND delete_flg = 0';
      $data = array(
        array(':id', $anime_id, PDO::PARAM_INT),
      );
      $stmt = queryPost($dbh, $sql, $data);
    } catch (PDOException $e) {
      debug('エラー発生：' . $e->getMessage());
    }
  }
}


try {
  // DB接続
  $dbh = dbConnect();
  $sql = 'SELECT id, title, status FROM anime WHERE user_id = :user_id AND delete_flg = 0 ';
  $data = array(
    array(':user_id', $user_id, PDO::PARAM_INT)
  );
  $stmt = queryPost($dbh, $sql, $data);
  $myanime_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
  debug(print_r($myanime_list, true));
} catch (PDOException $e) {
  debug('エラー発生：' . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="image/favicon.png" sizes="16x16" type="image/png">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/body.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/myanime.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <title>マイアニメ</title>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-E4NZNSFXJZ"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-E4NZNSFXJZ');
  </script>
</head>
<?php
include('header.php');
?>
<main id="main">
  <div class="container">
    <h1 class="page-title">マイアニメ</h1>
    <ul class="anime-list">
      <?php
      foreach ($myanime_list as $myanime) {
      ?>
        <li class="myanime-item">
          <p class="anime-title"><?php echo sanitize($myanime['title']); ?></p>
          <form action="" method="post" class="status-form">
            <input type="hidden" name="form-type" value="status">
            <input type="hidden" name="anime-id" value="<?php echo $myanime['id']; ?>">
            <select name="watch-status" class="watch-status" id="" onchange="submit(this.form)">
              <option hidden>選択してください</option>
              <option value="2" <?php if ($myanime['status'] === 2) echo 'selected'; ?>>見たい</option>
              <option value="3" <?php if ($myanime['status'] === 3) echo 'selected'; ?>>見ている</option>
              <option value="4" <?php if ($myanime['status'] === 4) echo 'selected'; ?>>見た</option>
            </select>
          </form>
          <form action="" method="post">
            <input type="hidden" name="form-type" value="delete">
            <input type="hidden" name="anime-id" value="<?php echo $myanime['id']; ?>">
            <input class="delete-btn" type="submit" value="削除">
          </form>
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