<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_start();
// Init
    require "class/PHPMailer.php";
    require "class/Exception.php";
    require "class/SMTP.php";
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    //mysql://bf04982af1559a:7130ee5d@us-cdbr-east-04.cleardb.com/heroku_9d8125d3f54cf1b?reconnect=true
    $settings = array(
        "host"               => "localhost",
        "name"               => "K04Team",
        "allow_email"        => ["student.tdtu.edu.vn", "tdtu.edu.vn"],
        "send_mail"          => "antikillvn@gmail.com",    
        "send_mail_password" => "taokobiet",   
    );
    
// Database
    $host = "us-cdbr-east-04.cleardb.com";
    $user = "bf04982af1559a";
    $pass = "7130ee5d";
    $db_name = "heroku_9d8125d3f54cf1b";
    
    $conn = new mysqli($host, $user, $pass, $db_name);
    $conn->set_charset("utf8");
    
    if (isset($_SESSION["username"])) {
        $users = $conn->query("SELECT * FROM users WHERE email = '{$_SESSION["username"]}'")->fetch_array();
        $rank = $users["rank"];
        
        if ($rank != "admin") {
            $giaovien = "giaovien = '". $_SESSION["username"] ."' AND";
        } else {
            $giaovien = "";
        }
    }

// Function
    function login($email, $password) {
        global $conn;
        if ($email == "" || $password == "") {
            return swal("Oops", "Vui lòng nhập đầy đủ thông tin! ". $username . $password, "warning", false);
        }
        if (!check($password)) {
            return swal("Oops", "Mật khẩu chứa ký tự không cho phép!", "warning", false);
        }
        $password = k04hash($password);
        $get = $conn->query("SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'");
        if ($get->num_rows > 0) {
            $_SESSION["username"] = $email;
            return swal("Thành công", "Đăng nhập thành công!", "success", true);
        } else {
            return swal("Thất bại", "Tài khoản hoặc mật khẩu không chính xác!", "error", false);
        }
    }
    
    function register($realname, $password, $email, $phone, $congtac) {
        global $conn, $settings;
        if ($email == "" || $password == "") {
            return swal("Oops", "Vui lòng nhập đầy đủ thông tin!", "warning", false);
        }
        if (!check($password)) {
            return swal("Oops", "Mật khẩu chứa ký tự không cho phép!", "warning", false);
        }
        if (!check($phone)) {
            return swal("Oops", "Số điện thoại chứa ký tự không cho phép!", "warning", false);
        }

        if (!in_array(explode("@", $email)[1], $settings["allow_email"])) {
            return swal("Oops", "Email của bạn không hợp lệ!", "warning", false);
        }

        $get = $conn->query("SELECT * FROM users WHERE phone = '{$phone}' OR email = '{$email}'");
        if ($get->num_rows > 0) {
            return swal("Thất bại", "Sô điện thoại hoặc email đã được sử dụng!", "error", false);
        } else {
            $password = k04hash($password);
            $date = date('Y-m-d h:i:s');
            $conn->query("INSERT INTO users(realname, password, email, phone, request_change_at, congtac) VALUES ('{$realname}', '{$password}', '{$email}', '{$phone}', '{$date}', '{$congtac}')");
            $_SESSION["username"] = $email;
            return swal("Thành công", "Đăng ký thành công!", "success", true);
        }
    }

    function forgot($email) {
        global $settings;
        global $conn;
        $get = $conn->query("SELECT * FROM users WHERE email = '{$email}'");
        if ($get->num_rows <= 0) {
            echo swal("Thất bại", "Email không tồn tại!", "error", true);
            return;
        }
        
        $subject = "Forgot password";
        $code = genCode(6);
        $link = "https://". $settings["host"] ."/forgot.php?code=". $code . "&email=". $email;
        $body = "Yêu cầu lấy lại mật khẩu cho: " . $email ." <br>Vui lòng truy cập đường link sau để đổi lại mật khẩu: <br>" . $link . "<br>Link này có thời hạn 24 giờ sau khi gửi đi<br>Thân!";
        
        $date = date("Y-m-d h:i:s");
        $conn->query("UPDATE users SET code = '{$code}', request_change_at = '{$date}' WHERE email = '{$email}'");
        
        mailer($email, $subject, $body);
    }

    function genCode($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function check($string) {
        $allow_char = "QWERTYUIOPASDFGHJKLZXCVBNM@qwertyuiopasdfghjklzxcvbnm1234567890_-.";
        $arr = str_split($allow_char);
        $char = str_split($string);
        $bool = true;
        foreach($char as $c) {
            if (!in_array($c, $arr)) {
                $bool = false;
                break;
            }
        }
        return $bool;
    }

    function k04hash($password) {
        $hash = hash('md5', 'I4104.').hash('sha256', $password);
        $hash = hash('sha256', $hash);
        return $hash;
    }
    
    function swal($title, $message, $type, $reload=false) {
        return json_encode(array("title" => $title, "message" => $message, "type" => $type, "reload" => $reload), JSON_UNESCAPED_UNICODE);
    }
    
    function format_str($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
         
        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ','_',$str);
        return $str;
    }
    
    function mailer($email, $subject, $body) {
        global $settings;
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0; $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true;
    	
          	$mail->Username = $settings["send_mail"]; $mail->Password = $settings["send_mail_password"];
          	
        	$mail->SMTPSecure = 'tls'; $mail->Port = 587;
          
        	$mail->setFrom($settings["send_mail"], $settings["name"]); $mail->addAddress($email); $mail->addReplyTo('no-reply@gmail.com', 'No Reply');
        	
        	$mail->CharSet = "utf-8";
          	$mail->isHTML(true);
        	$mail->Subject = $subject;
        	$mail->Body	   = $body;
        	$mail->send();
        	echo swal("Thành công", "Đã gửi mail thành công!", "success", true);
        } catch (Exception $e) {
            echo swal("Thất bại", $mail->ErrorInfo, "error", true);
        }
    }

?>