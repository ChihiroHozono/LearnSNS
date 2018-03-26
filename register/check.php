<?php
    session_start();
    // セッション変数に値が保存されていない場合、sigunp.phpへリダイレクト
    if(!isset($_SESSION['register'])){
    	// こいつでページを強制移動
    	header('Location:signup.php');
    	// 処理の終了
    	exit();
    }

    // ①
    $name = $_SESSION['register']['name'];
    $email = $_SESSION['register']['email'];
    $password = $_SESSION['register']['password'];
    $img_name = $_SESSION['register']['img_name'];

    // 登録ボタンが押された時の処理
    if(isset($_POST) && !empty($_POST)){
    	// データベースの接続
    	require('../db_connect.php');
    	// インサート文書き方が２通り（タプル形式、SET形式）
    	// SQLインジェクション
    	$sql = 'INSERT INTO `users` SET `name`=?, `email`=?, `password`=?, `img_name`=?, `created`=NOW()';
    	// インサート実行
    	$data = array($name, $email, password_hash($password, PASSWORD_DEFAULT), $img_name);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        // 使い終わったセッション変数を破棄
        // 変数の散財自体がなくなる
        unset($_SESSION['register']);

    	// thanks.phpへ移動
    	header("Location:thanks.php");
    	exit();
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">アカウント情報確認</h2>
        <div class="row">
          <div class="col-xs-4">
            <img src="../user_profile_img/<?php echo htmlspecialchars($img_name); ?>" class="img-responsive img-thumbnail">
          </div>
          <div class="col-xs-8">
            <div>
              <span>ユーザー名</span>
              <p class="lead"><?php echo htmlspecialchars($name); ?></p>
            </div>
            <div>
              <span>メールアドレス</span>
              <p class="lead"><?php echo htmlspecialchars($email); ?></p>
            </div>
            <div>
              <span>パスワード</span>
              <!-- ② -->
              <p class="lead">●●●●●●●●</p>
            </div>
            <!-- ③ -->
            <form method="POST" action="">
              <!-- ④ -->
              <a href="signup.php?action=rewrite" class="btn btn-default">&laquo;&nbsp;戻る</a> | 
              <!-- ⑤ -->
              <input type="hidden" name="action" value="submit">
              <input type="submit" class="btn btn-primary" value="ユーザー登録">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>