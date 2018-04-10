<?php
	session_start();
	// セッション変数の破棄
	$_SESSION = [];

	// クッキーの削除
	if(ini_get("session.user_cookies")){
		$params = session_get_cookie_params();
		setcookie(session_name(),"",time() - 42000,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);
	}
	setcookie('email',"",time() -3600);
	setcookie('password',"",time() -3600);


	session_destroy();


	// signin.phpへ移動
	header("Location: signin.php");
	exit();

 ?>