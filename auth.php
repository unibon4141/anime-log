<?php 
// ログイン認証
if(!empty($_SESSION['login_time'])){
  debug('ログイン済みのユーザーです');
  // ログイン有効期限（３０日）を超えていた場合
  if($_SESSION['login_time'] +60*60*24*30 < time()) {
    debug('ログイン有効期限切れユーザーです');
    // セッションを削除
    session_destroy();
    debug('ログインページへ遷移します');
    header('Location:login.php');
  } else {
    debug('ログイン有効期限内です');
    // ログイン日時を更新
    $_SESSION['login_time'] = time();
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('トップページへ遷移します');
      header('Location:index.php');
    }
  }
} else {
  debug('未ログインユーザーです');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
  // ログインページへ遷移
  debug('ログインページへ遷移します');
  header('Location:login.php');
  }
}

?>