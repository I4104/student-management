<?php
    include "../config.php";

    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    if ($action == "login") {
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        echo login($email, $password);
    }

    if ($action == "register") {
        $realname = isset($_POST["realname"]) ? $_POST["realname"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
        $congtac = isset($_POST["congtac"]) ? $_POST["congtac"] : "";
        echo register($realname, $password, $email, $phone, $congtac);
    }
    
    if ($action == "forgot") {
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        if ($email == "") {
            echo swal("Oops", "Không thể bỏ trống email", "error", true);
            return;
        }
        forgot($email);
    }
    
    if ($action == "change") {
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        if ($email == "") {
            echo swal("Oops", "Không thể bỏ trống email", "error", true);
            return;
        }
        $password = k04hash($password);
        $conn->query("UPDATE users SET password = '{$password}', code = 'none' WHERE email = '{$email}'");
        echo swal("Thành công", "Cập nhật thông tin thành công", "success", true);
    }
    
    if ($action == "edit") {
        $realname = isset($_POST["realname"]) ? $_POST["realname"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
        $congtac = isset($_POST["congtac"]) ? $_POST["congtac"] : "";
        $rank = isset($_POST["rank"]) ? $_POST["rank"] : "";
        
        $conn->query("UPDATE users SET realname = '{$realname}', phone = '{$phone}', congtac = '{$congtac}', rank = '{$rank}' WHERE email = '{$email}'");
        echo swal("Thành công", "Cập nhật thông tin thành công", "success", true);
    }
    
    if ($action == "delete") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        if ($id == "") {
            echo swal("Thất bại", "Người dùng không tồn tại", "error", true);
        } else {
            $conn->query("DELETE FROM users WHERE id = '{$id}'");
            echo swal("Thành công", "Đã xóa tài khoản", "success", true);
        }
    }
    
    if ($action == "logout") {
        unset($_SESSION);
        session_destroy();
    }
?>