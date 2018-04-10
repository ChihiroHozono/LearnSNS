<?php
  session_start();
  require('db_connect.php');
  require('signin_check.php');

  // ナビバーに表示するためログインユーザーの情報を取得

  // SQL文
  $sql = 'SELECT * FROM `users` WHERE `id` ='.$_SESSION['id'];
  // SQL文の実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  $login_user = $stmt->fetch(PDO::FETCH_ASSOC);

  // imgファイルのパス
  $img_path = 'user_profile_img/'.$login_user['img_name'];



  // 投稿するを押した時にPOST送信されつぶやきを保存
  if(isset($_POST) && !empty($_POST)){
    // var_dump($_POST["feed"]);
    // データベースに接続
    // 変数に代入
    $feed = $_POST['feed'];
    $user_id = $login_user['id'];
    $img_name = $img_path;

    require('db_connect.php');

    // テーブルにデータを追加
    $sql = 'INSERT INTO `feeds` SET `feed`=?, `user_id`=?,`created`= NOW()';
      // インサート実行
    $data = array($feed,$user_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
  }

  // つぶやきをデータベースから取得
  // 必要なデータ
  // 画像、名前、コメントuser_idとコメントのidを結びつける
    $sql = 'SELECT * FROM `feeds`';



// つぶやきの取得
        // timelineの情報を取得
    // 最新情報順にデータを取得
    $sql ='SELECT `feeds`.*,`users`.`name`,`users`.`img_name` as `profile_img` FROM `feeds` INNER JOIN `users` ON `feeds`.`user_id` = `users`.`id` ORDER BY `feeds`.`created` DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // タイムラインの情報を格納
    $timeline = array();
    while(1){
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);

      // 最後まで来たら終了
      if($rec == false){
        break;
      }

      // ログインユーザーが現在取得フィードにライクしているかどうか
      // $_SESSION['id'] ログインしているユーザーのID
      // $rec['id'] 現在取得したfeedのID

      $like_sql = "SELECT COUNT(*) as `cnt` FROM `Likes` WHERE `feed_id` = ? AND `user_id` = ? ";
      $like_data = array($rec["id"],$_SESSION["id"]);
      $like_stmt = $dbh->prepare($like_sql);
      $like_stmt->execute($like_data);

      $like_rec = $like_stmt->fetch(PDO::FETCH_ASSOC);

      if($like_rec["cnt"] == 0){
        // ライクしてない
        $rec["like_flag"] = 0;
      }else{
        // ライク済み
        $rec["like_flag"] = 1;
      }

      // いいね済みのみの表示指示された時、ログインユーザーがいいね！済みのデータだけをタイムラインに表示するようにする。

      if((isset($_GET["feed_select"]) && $_GET["feed_select"] == "likes")){
        // いいねしたツイートのみを表示
        if($rec["like_flag"] == 1){
          $timeline[] = $rec;
        }

      }else{
        // 配列にデータを追加
        // $timelineに入ったデータがタイムラインに表示される
        $timeline[] = $rec;

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
<body style="margin-top: 60px; background: #E4E6EB;">
  
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Learn SNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">タイムライン</a></li>
          <li><a href="#">ユーザー一覧</a></li>
        </ul>
        <form method="GET" action="" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" name="search_word" class="form-control" placeholder="投稿を検索">
          </div>
          <button type="submit" class="btn btn-default">検索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $img_path; ?>" width="18" class="img-circle"><?php echo $login_user['name']; ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">マイページ</a></li>
              <li><a href="signout.php">サインアウト</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">
          <?php if(!isset($_GET["feed_select"]) || (isset($_GET["feed_select"]) && $_GET["feed_select"] == "news")){ ?>
            <li class="active">
          <?php }else{ ?>
            <li >
          <?php } ?>
            <a href="timeline.php?feed_select=news">新着順</a></li>
          <?php if((isset($_GET["feed_select"]) && $_GET["feed_select"] == "likes")){ ?>
            <li class="active">
          <?php }else{ ?>
            <li>
          <?php }?>
          <a href="timeline.php?feed_select=likes">いいね！済み</a></li>
         <!--  <li><a href="timeline.php?feed_select=follows">フォロー</a></li>
 -->        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="timeline.php">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
          </form>
        </div>

        <!-- 件数分繰り返し -->
        <?php  foreach ($timeline as $timeline_each){
          // phpを読み込み
          include("timeline_one.php");
        } ?>


        <nav aria-label="Page navigation">
          <ul class="pager">
            <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Older</a></li>
            <li class="next"><a href="#">Newer <span aria-hidden="true">&rarr;</span></a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>