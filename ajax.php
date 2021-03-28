<!-- ajax -->
<?php
  include('function.php');
  if(!empty($_POST)){
    debug('POST: '.print_r($_POST, true));
    $title = $_POST['title'];
    if(isLogin()){
    try{
      $u_id = $_SESSION['user_id'];
      $dbh = dbConnect();
      $sql = 'INSERT INTO anime (user_id, title, status, created_at ) VALUES(:user_id, :title, :status, :created_at)'; 
      $data = array(
        array(':user_id', $u_id, PDO::PARAM_INT),
        array(':title', $title, PDO::PARAM_STR),
        array(':status', 1, PDO::PARAM_INT),
        array(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR)
      );
      $stmt = queryPost($dbh, $sql, $data);    
    
    } catch(PDOException $e){
      debug('エラー発生：'.$e->getMessage());
    }
  }
  }
?>