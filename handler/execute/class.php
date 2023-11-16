<?php

    include "../config.php";
    
    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    if ($action == "add") {
        $tenlop = isset($_POST["tenlop"]) ? $_POST["tenlop"] : "";
        $monhoc = isset($_POST["monhoc"]) ? $_POST["monhoc"] : "";
        $phonghoc = isset($_POST["phonghoc"]) ? $_POST["phonghoc"] : "";
        
        // Kiểm tra load file
        $allow = array("jpg", "png", "jpeg");
        $file = "";
        list($name, $ext) = explode('.', $_FILES['img']['name']);
        
        if (!in_array($ext, $allow)) {
            echo swal("Thất bại", "File bạn nhập không đúng định dạng", "error", false);
            return;
        } else {
            if (isset($_FILES["img"]) && $_FILES['img']['tmp_name'] != "") {
                if ($_FILES['img']['error'] > 0) {
                    return;
                } else {
                    if (!file_exists("../../resource/class")) {
                        mkdir("../../resource/class", 0777, true);
                    }
                    $file = format_str($name);
                    $file = hash('sha256', $file.rand(0, 100)).".".$ext;
                    move_uploaded_file($_FILES['img']['tmp_name'], "../../resource/class/". $file);
                }
            }
        }
        
        $code = genCode(6);
        while($conn->query("SELECT * FROM `classroom` WHERE code = '{$code}'")->num_rows > 0) {
            $code = genCode(6);
        }
        
        $conn->query("INSERT INTO `classroom`(`tenlop`, `monhoc`, `phonghoc`, `image`, `code`, `giaovien`) VALUES ('{$tenlop}', '{$monhoc}', '{$phonghoc}', '{$file}', '{$code}', '{$_SESSION["username"]}')");
        
        echo swal("Thành công", "Đã thêm lớp học mới", "success", true);
    }
    
    if ($action == "edit") {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $get = $conn->query("SELECT * FROM classroom WHERE id = '{$id}'");
        
        if ($get->num_rows > 0) {
            $row = $get->fetch_array();
            $tenlop = isset($_POST["tenlop"]) ? $_POST["tenlop"] : "";
            $monhoc = isset($_POST["monhoc"]) ? $_POST["monhoc"] : "";
            $phonghoc = isset($_POST["phonghoc"]) ? $_POST["phonghoc"] : "";
            
            $file = $row["image"];
            
            if ($_FILES['img']['name'] != "") {
                $allow = array("jpg", "png", "jpeg");
                $file = "";
                list($name, $ext) = explode('.', $_FILES['img']['name']);
                
                if (!in_array($ext, $allow)) {
                    echo swal("Thất bại", "File bạn nhập không đúng định dạng", "error", false);
                    return;
                } else {
                    if (isset($_FILES["img"]) && $_FILES['img']['tmp_name'] != "") {
                        if ($_FILES['img']['error'] > 0) {
                            return;
                        } else {
                            if (!file_exists("../../resource/class")) {
                                mkdir("../../resource/class", 0777, true);
                            }
                            $file = format_str($name);
                            $file = hash('sha256', $file.rand(0, 100)).".".$ext;
                            move_uploaded_file($_FILES['img']['tmp_name'], "../../resource/class/". $file);
                        }
                    }
                }
            }
            $conn->query("UPDATE `classroom` SET `tenlop` = '{$tenlop}', `monhoc` = '{$monhoc}', `phonghoc` = '{$phonghoc}', `image` = '{$file}' WHERE id = '{$id}'");
            echo swal("Thành công", "Đã sửa thông tin lớp học", "success", true);
        } else {
            echo swal("Thất bại", "Lớp học này không tồn tại!", "error", true);
        }
    }
    
    if ($action == "delete") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        $get = $conn->query("SELECT * FROM classroom WHERE id = '{$id}'");
        if ($get->num_rows > 0) {
            $row = $get->fetch_array();
            $conn->query("DELETE FROM `classroom` WHERE id = '{$id}'");
            $conn->query("DELETE FROM `class_student` WHERE lophoc = '{$row["code"]}'");
            if (file_exists("../../resource/class/". $row["image"])) {
                unlink("../../resource/class/". $row["image"]);
            }
            echo swal("Thành công", "Đã xóa lớp học: ". $row["tenlop"], "success", true);
        } else {
            echo swal("Thất bại", "Lớp học này không tồn tại!", "error", true);
        }
    }
    
?>