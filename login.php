<?php
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//  ログインページ
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
include('function.php');
debug('ログインページ');
include('auth.php');

if (!empty($_POST)) {
  debug('POST送信があります');
  debug('POST内容：' . print_r($_POST, true));

  // 変数にユーザー情報を格納
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  // バリデーションチェック
  // 未入力チェック
  requireValid($email, 'email');
  requireValid($pass, 'pass');

  if (empty($errMsg)) {
    // メールアドレス形式チェック
    emailFormatValid($email, 'email');
    // パスワード文字数チェック
    strLenValid($pass, 'pass');

    if (empty($errMsg)) {
      // 入力されたメールアドレスとパスワードに一致するユーザーがいるかを検索
      // 例外処理
      try {
        $dbh = dbConnect();
        $sql = 'SELECT id, password  FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(
          array(':email', $email, PDO::PARAM_STR),
        );
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        debug('SQL結果：' . print_r($result, true));
        $user_id = (!empty($result)) ? (int)$result['id'] : '';
        $db_path = (!empty($result)) ? $result['password'] : '';
        debug($pass);
        if (!empty($user_id) && password_verify($pass, $db_path)) {
          debug('バリデーションOKです');
          debug('ログイン情報と合致するユーザーが見つかりました。');
          $_SESSION['login_time'] = time();
          $_SESSION['user_id'] = $user_id;
          debug('トップページへ遷移します');
          header('Location:index.php');
        } else {
          debug('入力情報と一致するユーザーが見つかりませんでした。');
          $errMsg['pass'] = MSG05;
        }
      } catch (PDOException $e) {
        debug('エラー発生：' . $e->getMessage());
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="image/favicon.png" sizes="16x16" type="image/png">
  <link rel="stylesheet" href="css/login_signUp.css">
  <title>ログイン</title>
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

<body>
  <header>
    <div class="logo-image">
      <img src="image/logo_small.png" alt="ロゴ">
    </div>
  </header>
  <main id="id">

    <div class="login-form-over-wrap">
      <div class="login-form-wrap">
        <div class="login-form-content">
          <h2 class="page-title">ログイン</h2>
          <form action="" method="post">
            <span class="form-item-name"><label>メールアドレス</label></span>
            <input class="login-form-item" type="text" name="email">
            <p class="error-msg"><?php if (!empty($errMsg['email'])) echo '※' . $errMsg['email']; ?></p>
            <span class="form-item-name"><label>パスワード</label></span>
            <input class="login-form-item" type="password" name="pass">
            <p class="error-msg"><?php if (!empty($errMsg['pass'])) echo '※' . $errMsg['pass']; ?></p>
            <input class="login-btn" type="submit" value="ログイン">
          </form>
        </div>
      </div>
      <a class="link-top-page" href="index.php">トップページへ戻る</a>
    </div>
  </main>
</body>

</html>