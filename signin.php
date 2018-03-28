<?php
  // セッション変数を使うための宣言
  session_start();
    // サインイン処理
  $errors = array();

  $input_email = '';
  $input_password ='';
  if(!empty($_POST)){
    $input_email = $_POST["input_email"];
    $input_password = $_POST["input_password"];

    if($input_password==''){
      $errors['input_password'] = 'blank';
    }

    // emilの空チェック
    if($input_email==''){
      $errors['input_email'] = 'blank';
    }

    if(empty($errors)){
      // emailでデータベースからデータを取得
      require('db_connect.php');


      $sql = 'SELECT * FROM `users` WHERE `email` = ?';
      $data = array($input_email);
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);

      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      // 一軒も取れないと$recにfalseが入る
        if($rec == false){
          $errors['signin'] = 'failed';
        }else{
          if(password_verify($password,$rec['password'])){
            // 認証成功

            // セッションにIDを保存
            // 他の情報はその都度取ってくる方が新しい情報が取れる
            $_SESSION['id'] = $rec['id'];
            // timeline.phpに移動
            header('Location:timeline.php');
            }else{
              $errors['siginin'] = 'failed';
            }
        }
      }

    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">サインイン</h2>
        <form method="POST" action="signin.php" enctype="multipart/form-data">
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if((isset($errors["input_email"]))&&($errors["input_email"]=='blank')){ ?>
              <p class="text-danger">メールアドレスを入力してください</p>
            <?php  }?>
            <?php if((isset($errors["signin"]))&&($errors["signin"]=='failed')){ ?>
              <p class="text-danger">ログインに失敗しました。入力情報を確認してください</p>
            <?php  }?>
          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            <?php if((isset($errors["input_password"]))&&($errors["input_password"]=='blank')){ ?>
              <p class="text-danger">パスワードを入力してください</p>
            <?php  }?>
          </div>
          <input type="submit" class="btn btn-info" value="サインイン">
        </form>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>