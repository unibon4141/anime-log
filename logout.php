<?php
// ===================
// ログアウト機能
include('function.php');
session_destroy();
debug('ログアウトしました。');
debug('ログインページへリダイレクトします');
header('Location:login.php');
?>