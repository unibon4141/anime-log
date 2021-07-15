<?php
// ======================
// エラーログを表示する
ini_set('log_errors', 'on');
//エラーレベルを設定
ini_set('error_reporting', E_ALL);
// ログをファイルに保存
ini_set('error_log', './log/php.log');



ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
ini_set('session.cookie_lifetime ', 60 * 60 * 24 * 30);

// セッション開始
session_start();
session_regenerate_id();

$errMsg = array();

define('MSG01', '入力必須です。');
define('MSG02', 'メールアドレスを正しく入力してください。');
define('MSG03', 'パスワードは6文字以上12文字以内で入力してください。');
define('MSG04', '既に使用されているメールアドレスです。');
define('MSG05', 'メールアドレスまたはパスワードが間違っています。');

//ログの出力変更用フラグ
$debugFlg = 1;
// ログを出力
function debug($str)
{
  global $debugFlg;
  if ($debugFlg) {
    error_log($str);
  }
}

// DBに接続
function dbConnect()
{
  $db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
  $db['dbname'] = ltrim($db['path'], '/');
  $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
  $username = $db['user'];
  $password = $db['pass'];
  $options = array(
    // SQLの実行によりエラーが発生したときは例外を投げる
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // デフォルトフェッチモード(DBからデータを受け取るときの配列の形式）を連想配列形式にする 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // エミュレートをオフにして静的プレースホルダを使う
    PDO::ATTR_EMULATE_PREPARES => false,
  );
  $dbh = new PDO($dsn, $username, $password, $options);
  return $dbh;
}

function queryPost($dbh, $sql, $data)
{
  $stmt = $dbh->prepare($sql);
  foreach ($data as $d) {
    $stmt->bindValue($d[0], $d[1], $d[2]);
  }
  $result = $stmt->execute();
  if ($result) {
    debug('SQLは成功しました。');
  } else {
    debug('SQLは失敗しました。');
    debug('SQLエラー：' . $stmt->errorInfo());
  }
  return $stmt;
}

// メールアドレス重複チェック
function emailValidDup($email)
{
  global $errMsg;
  try {
    $dbh = dbConnect();
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(
      array(':email', $email, PDO::PARAM_STR)
    );
    $stmt = queryPost($dbh, $sql, $data);
    // 結果セットから取り出したデータを連想配列形式で取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    debug(print_r($result, true));

    // 重複したメールアドレスがDBに存在した場合
    if ($result['count(*)'] > 0) {
      $errMsg['email'] = MSG04;
    }
  } catch (PDOException $e) {
    error_log('エラー発生：' . $e->getMessage());
  }
}

// 未入力チェック
function requireValid($str, $name)
{
  global $errMsg;
  if (empty($str)) {
    $errMsg[$name] = MSG01;
  }
}

// メールアドレス形式チェック
function emailFormatValid($email, $name)
{
  global $errMsg;
  if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\?\*\[|\]%'=~^\{\}\/\+!#&\$\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\._-]+)+$/", $email)) {
    $errMsg[$name] = MSG02;
  }
}

// 文字数チェック
function strLenValid($str, $name, $min = 6, $max = 12)
{
  global $errMsg;
  if (mb_strlen($str) < $min  || mb_strlen($str) > $max) {
    $errMsg[$name]  = MSG03;
  }
}

function isLogin()
{
  if (!empty($_SESSION['login_time'])) {
    debug('ログイン済みのユーザーです');
    // ログイン有効期限（３０日）を超えていた場合
    if ($_SESSION['login_time'] + 60 * 60 * 24 * 30 < time()) {
      $isLogin = 0;
    } else {
      debug('ログイン有効期限内です');
      // ログイン日時を更新
      $isLogin = 1;
    }
  } else {
    debug('未ログインユーザーです');
    $isLogin = 0;
  }
  return $isLogin;
}

// サニタイズ
function sanitize($str)
{
  return htmlspecialchars($str, ENT_QUOTES);
}
