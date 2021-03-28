<body>
  <header>
    <div class="container">
      <div class="header-top">
        <div class="logo">
          <img src="image/logo.jpg" alt="ロゴ">
        </div>
        <div class="start">
          <ul class="start-list">
            <li class="<?php if(isLogin()) echo 'hidden'; ?>"><a href="login.php">ログイン</a></li>
            <li class="<?php if(isLogin()) echo 'hidden'; ?>"><a href="sign-up.php">ユーザー登録</a></li>
            <li class="<?php if(!isLogin()) echo 'hidden'; ?>"><a href="logout.php">ログアウト</a></li>
          </ul>
        </div>
      </div>
      <nav>
        <ul class="header-nav-list">
          <li><a href="index.php">今期のアニメ一覧</a></li>
          <li><a href="search.php">アニメを探す</a></li>
          <li><a href="myanime.php">マイアニメ</a></li>
        </ul>
      </nav>
    </div><!-- container -->
  </header>