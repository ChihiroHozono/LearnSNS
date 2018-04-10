<?php
	session_start();
  require('db_connect.php');
  require('signin_check.php');

  // いいね機能


  // 誰がどの記事にいいねしたのか
  // var_dump($_REQUEST["feed_id"]);
  $id = $_SESSION['id'];
  $feed_id = $_REQUEST["feed_id"];
  // SETを使う方法もありcheckphp参照
  $sql = "INSERT INTO `likes` (`user_id`, `feed_id`) VALUES ('$id','$feed_id')";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

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