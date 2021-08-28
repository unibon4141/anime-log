<?php
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//  ユーザー登録ページ
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
include('function.php');


// POST送信はされているか
if (!empty($_POST)) {
  debug('POST送信があります。');
  debug('POST' . print_r($_POST, true));

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

    // メールアドレス重複チェック
    emailValidDup($email);

    if (empty($errMsg)) {
      debug('バリデーションOKです。');
      // パスワードをハッシュ化する
      $hash_path = password_hash($pass, PASSWORD_DEFAULT);
      // 例外処理
      try {
        // DB接続
        $dbh = dbConnect();
        $sql = 'INSERT INTO users (email, password, created_at) VALUES (:email, :password, :created_at)';
        $data = array(
          array(':email', $email, PDO::PARAM_STR),
          array(':password', $hash_path, PDO::PARAM_STR),
          array(':created_at', date('Y-m-d'), PDO::PARAM_STR),
        );
        $stmt = queryPost($dbh, $sql, $data);



        $_SESSION['user_id'] = $dbh->lastInsertId();
        debug('last:' . $dbh->lastInsertId());
        $_SESSION['login_time'] = time();
        debug('セッション情報：' . print_r($_SESSION, true));
        //トップページへ遷移する
        header('Location:index.php');
      } catch (PDOException $e) {
        error_log($e->getMessage());
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
  <title>ユーザー登録</title>
</head>

<body>
  <header>
    <div class="logo-image">
      <img src="image/logo_transparent.png" alt="ロゴ">
    </div>
  </header>
  <main>
    <div class="sign-up-form-over-wrap">
      <div class="sign-up-form-wrap">
        <div class="sign-up-form-content">
          <h2 class="page-title">ユーザー登録</h2>
          <form action="" method="post">
            <span class="form-item-name"><label>メールアドレス</label></span>
            <input class="sign-up-form-item" type="text" name="email" placeholder="XXX@YYY.com">
            <p class="error-msg"><?php if (!empty($errMsg['email'])) echo '※' . $errMsg['email']; ?></p>
            <span class="form-item-name"><label>パスワード</label></span>
            <input class="sign-up-form-item" type="password" name="pass" placeholder="6文字以上12文字以内">
            <p class="error-msg"><?php if (!empty($errMsg['pass'])) echo '※' . $errMsg['pass']; ?></p>
            <input class="sign-up-btn" type="submit" value="登録">
          </form>
        </div>
      </div>
      <a class="link-top-page" href="index.php">トップページへ戻る</a>
    </div>
  </main>
</body>

</html>