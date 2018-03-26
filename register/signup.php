<?php
// 基本的には一番上にかく
  session_start();

  // $eroorsを初期化
  $errors = array();
  // 書き直し処理
  // check.phpから戻るボタンが押された時
  // $_REQUEST GET,POST送信データが格納されている変数
  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite'){
    $_POST["input_name"] = $_SESSION['register']['name'];
    $_POST['input_email'] = $_SESSION['register']['email'];
    $_POST['input_password'] = $_SESSION['register']['password'];
    $errors['rewrite'] = true;

  }


  $name = '';
  $input_email = '';
  $input_password ='';
  // 入力の不備をチェック
  if(!empty($_POST)){
    $name = $_POST["input_name"];
    $input_email = $_POST["input_email"];
    $input_password = $_POST["input_password"];
    $count = strlen($input_password);
    // ユーザー名の空チェック
    if($name == ''){
      $errors['name'] = 'blank';
    }
    // emilの空チェック
    if($input_email==''){
      $errors['input_email'] = 'blank';
    }

    // パスワードのチェック
    if($input_password ==''){
      $errors['input_password'] = 'blank';
    }elseif($count < 4 || $count > 16 ){
      $errors['input_password'] = 'length';
    }
    // 画像のチェック
    // 変数なしエラーを回避するため
    $file_name = '';
    if(!isset($_REQUEST['action'])){
        $file_name = $_FILES['input_img_name']['name'];
    }

    if(!empty($file_name)){
      $file_type = substr($file_name, -3);
      $file_type = strtolower($file_type);
      if(($file_type!='jpg') && ($file_type!='png') && ($file_type != 'gif')){
        $errors['img_name'] = 'type';
      }
    }else{
      $errors['img_name'] = 'blank';
    }

    // エラーがない場合、正常処理
    if(empty($errors)){
      $date_str = date('YmdHis');
      $submit_file_name = $date_str.$file_name;

      move_uploaded_file($_FILES['input_img_name']['tmp_name'], '../user_profile_img/' . $submit_file_name);

      // 二重の連想配列にしているのは後から消しやすいようにするため
      $_SESSION['register']['name'] = $_POST['input_name'];
      $_SESSION['register']['email'] = $_POST['input_email'];
      $_SESSION['register']['password'] = $_POST['input_password'];

      $_SESSION['register']['img_name'] = $submit_file_name;

      // 移動
      // Locationの後に移動したいpageのURLをかく
      header('Location:check.php');
      // これ以降の処理が停止する
      exit();

    }

  }


?>


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
      <!-- ここにコンテンツ -->
 <!-- ここから -->
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">アカウント作成</h2>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎" value="<?php echo $name; ?>">
            <?php if((isset($errors["name"]))&&($errors["name"]=='blank')){ ?>
              <p class="text-danger">ユーザー名を入力してください</p>
            <?php  }?>
          </div>
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com" value="<?php echo $input_email; ?>">
            <?php if((isset($errors["input_email"]))&&($errors["input_email"]=='blank')){ ?>
              <p class="text-danger">メールアドレスを入力</p>
            <?php  }?>
          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード" value="<?php echo $input_password; ?>">
            <?php if((isset($errors["input_password"]))&&($errors["input_password"]=='blank')){ ?>
              <p class="text-danger">パスワードを入力してください</p>
            <?php  }?>
            <?php if((isset($errors["input_password"]))&&($errors["input_password"]=='length')){ ?>
              <p class="text-danger">パスワードは4 ~ 16文字で入力してください</p>
            <?php  }?>
          </div>
          <div class="form-group">
            <label for="img_name">プロフィール画像</label>
            <input type="file" name="input_img_name" id="img_name"  accept="image/*">
            <?php if((isset($errors["rewrite"]))&&($errors["rewrite"]==true)){ ?>
              <p class="text-danger">画像は再選択してください</p>
            <?php  }?>
            <?php if((isset($errors["img_name"]))&&($errors["img_name"]=='blank')){ ?>
              <p class="text-danger">画像を選択してください</p>
            <?php  }?>
            <?php if((isset($errors["img_name"]))&&($errors["img_name"]=='type')){ ?>
              <p class="text-danger">画像の種類を確認してください</p>
            <?php  }?>
          </div>
          <input type="submit" class="btn btn-default" value="確認">
          <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
        </form>
      </div>
      <!-- ここまで -->

    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>