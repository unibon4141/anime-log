<?php
// ===================
// ログアウト機能
include('function.php');
session_destroy();
debug('ログアウトしました。');
debug('トップページへリダイレクトします');
header('Location:index.php');
?>