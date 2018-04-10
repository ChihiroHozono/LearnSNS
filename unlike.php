<?php
	session_start();
  require('db_connect.php');
  require('signin_check.php');

  // いいねを取り消す機能


  // 誰がどの記事を取り消したいのか消去
  // var_dump($_REQUEST["feed_id"]);
  $id = $_SESSION['id'];
  $feed_id = $_REQUEST["feed_id"];
  // SETを使う方法もありcheckphp参照
  $sql = "DELETE FROM `likes`  WHERE `user_id` = ? AND `feed_id` = ?";
  $data = array($_SESSION["id"],$_REQUEST["feed_id"]);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);

  // イイねされた記事のlike_countを再計算
  // ダブルクオーテーションで囲むと変数展開できる
  $sql = "SELECT COUNT(*) as `cnt` FROM `likes` WHERE `feed_id` = $feed_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
  $sum = $rec["cnt"];
  // var_dump($sum);


  // イイねされた数を取得
  // SETのみを使う
  $sql = "UPDATE `feeds` SET `like_count` = $sum WHERE `id` = $feed_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();


  // イイねされた記事のlike_countを


  // timeline.phpに戻る
  header("Location: timeline.php");


 ?>