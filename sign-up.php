<?php 
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//  ユーザー登録ページ
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
// エラーログを表示する
ini_set('log_errors', 'on');
//エラーレベルを設定
ini_set('error_reporting', E_ALL);
// ログをファイルに保存
ini_set('error_log', 'php.log');

// ログを出力
function debug($str) {
  error_log($str);
}

// POST送信はされているか
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST'.print_r($_POST, true));

  // 変数にユーザー情報を格納
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  $errMsg = array();

  define('MSG01','入力必須です。');
  define('MSG02','メールアドレスを正しく入力してください。');
  define('MSG03','パスワードは6文字以上12文字以内で入力してください。');
  // バリデーションチェック
  
  if(empty($email)){
    $errMsg['email'] = MSG01;
  }
  if(empty($pass)){
    $errMsg['pass'] = MSG01;
  }

  if(empty($errMsg)){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\?\*\[|\]%'=~^\{\}\/\+!#&\$\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\._-]+)+$/",$email)){
      $errMsg['email'] = MSG02;
    }
    if(mb_strlen($pass) < 6  || mb_strlen($pass) > 12){
      $errMsg ['pass']  = MSG03;
    }
    if(empty($errMsg)){
      debug('バリデーションOKです。');
      try{
        // DB接続
    $dsn = 'mysql:host=localhost:3308;dbname=anime_log;charset=utf8';

    $username = 'root';
    $password = 'root';
    $options = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      // エミュレートをオフにして静的プレースホルダを使う
      PDO::ATTR_EMULATE_PREPARES => false,
    );

    $dbh = new PDO($dsn, $username, $password, $options);

    $stmt = $dbh->prepare('INSERT INTO users (email, password, created_at) VALUES (:email, :password, :created_at)');
    $result = $stmt->execute(
      array(
        ':email' => $email,
        ':password' => $pass,
        ':created_at' => date('Y-m-d'),
      )

      );
  } catch (PDOException $e){
    error_log($e->getMessage());
  }
    }

  
  }  

    debug('エラー内容:'.print_r($errMsg, true));
  
  
}




?> 
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/sign-up.css">
  <title>ユーザー登録</title>
</head>
<body>
  <header>
    <div class="logo">
      <img src="image/logo.jpg" alt="ロゴ">
    </div>
  </header>
  <main id="id">
  <h2>ユーザー登録</h2>
    <form action="" method="post">
      <label>メールアドレス</label>
      <input type="text" name="email" placeholder="XXX@YYY.com"><br>
      <label>パスワード</label>
      <input type="password" name="pass" placeholder="6文字以上12文字以内"><br>
      <input type="submit" value="登録">
    </form>
  </main>
</body>
</html>