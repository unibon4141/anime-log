<body>
  <header>
    <div class="container">
      <div class="header-top">
        <div class="logo">
          <img src="image/logo.jpg" alt="ロゴ">
        </div>
        <div class="start">
          <ul class="start-list">
            <li class="<?php if ($loginFlg) echo 'hidden'; ?>"><a class="nav-btn" href="login.php">ログイン</a></li>
            <li class="<?php if ($loginFlg) echo 'hidden'; ?>"><a class="nav-btn" href="sign-up.php">ユーザー登録</a></li>
            <li class="<?php if (!$loginFlg) echo 'hidden'; ?>"><a class="nav-btn" href="logout.php">ログアウト</a></li>
          </ul>
        </div>
      </div>
      <nav>
        <ul class="header-nav-list">
          <li><a class="page-btn" href="index.php">今期のアニメ</a></li>
          <li><a class="page-btn" href="search.php">アニメを探す</a></li>
          <li><a class="page-btn" href="myanime.php">マイアニメ</a></li>
        </ul>
      </nav>
    </div><!-- container -->
  </header>