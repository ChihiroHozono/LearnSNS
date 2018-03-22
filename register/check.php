

<?php
session_start();
echo $_SESSION['register']['name'].'<br>';
echo $_SESSION['register']['email'].'<br>';
echo $_SESSION['register']['password'].'<br>';
echo $_SESSION['register']['img_name'].'<br>';

?>



<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>

	<img src="../user_profile_img/<?php echo $_SESSION['register']['img_name'];?>" width="60">

</body>
</html>

本物
http://localhost/LearnSNS/user_profile_img/20180322022602%25a6JVCVDSTGTi5ZQzQ+8Ow.jpg


偽物
http://localhost/LearnSNS/user_profile_img/20180322022602%a6JVCVDSTGTi5ZQzQ+8Ow.jpg