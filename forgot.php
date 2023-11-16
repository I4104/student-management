<?php
    include "handler/config.php";
    if (isset($_SESSION["username"])) header("location: index.php");
    if (!isset($_GET["email"], $_GET["code"])) header("location: index.php");
    
    $email = $_GET["email"]; $code = $_GET["code"];
    $get = $conn->query("SELECT * FROM users WHERE email = '{$email}' AND code = '{$code}'");
    if ($get->num_rows > 0 && $get->fetch_array()["code"] != "none") {
        $row = $get->fetch_array();
        $request_at = $row["request_change_at"]; 
        $request_at = strtotime($request_at);
        $exp = $request_at + (24 * 60 * 60);
        $now = strtotime(date("Y-m-d h:i:s"));
        if ($now > $exp) {
            $conn->query("UPDATE users SET code = 'none' WHERE email = '{$email}'");
            echo "<script>alert('Yêu cầu đã hết hạn hoặc không tồn tại'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Yêu cầu đã hết hạn hoặc không tồn tại'); window.location.href='index.php';</script>";
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animsition@4.0.2/dist/css/animsition.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/1.1.3/hamburgers.min.css" integrity="sha512-+mlclc5Q/eHs49oIOCxnnENudJWuNqX5AogCiqRBgKnpoplPzETg2fkgBFVC6WYUVxYYljuxPNG8RE7yBy1K+g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.css" integrity="sha512-phGxLIsvHFArdI7IyLjv14dchvbVkEDaH95efvAae/y2exeWBQCQDpNFbOTdV1p4/pIa/XtbuDCnfhDEIXhvGQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="style.css">
<!--===============================================================================================-->
</head>
<body>
	<div class="limiter">
		<div class="container-login">
			<div class="wrap-login">
				<form class="login-form validate-form" method="POST" id="change">
					<span class="login-form-title p-b-43">
						Reset Password
					</span>
					<div class="wrap-input validate-input" data-validate="Vui lòng nhập email đúng định dạng">
						<input class="input" type="email" name="email" value="<?php echo $email; ?>" readonly>
						<span class="focus-input"></span>
						<span class="label-input">Email</span>
					</div>
					<div class="wrap-input validate-input" data-validate="Vui lòng nhập mật khẩu đúng định dạng">
						<input class="input" type="password" name="password">
						<span class="focus-input"></span>
						<span class="label-input">Password</span>
					</div>
					<div class="wrap-input validate-input" data-validate="Vui lòng nhập mật khẩu đúng định dạng">
						<input class="input" type="password" name="repassword">
						<span class="focus-input"></span>
						<span class="label-input">Re-enter Password</span>
					</div>
					<div class="container-login-form-btn">
						<button class="login-form-btn" type="submit">
							Change
						</button>
					</div>
				</form>
				<div class="login-more"></div>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!--===============================================================================================-->
	<script src="https://cdn.jsdelivr.net/npm/animsition@4.0.2/dist/js/animsition.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!--===============================================================================================-->
	<script src="main.js"></script>

</body>
</html>