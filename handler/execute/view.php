<?php
    include "../config.php";

    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    if ($action == "add") {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $row = $conn->query("SELECT * FROM `classroom` WHERE id = '{$id}'")->fetch_array();
        
        $name = isset($_POST["name"]) ? $_POST["name"] : "";
        $sotiet = isset($_POST["sotiet"]) ? $_POST["sotiet"] : "";
        
        $conn->query("INSERT INTO `buoihoc`(name, sotiet, lophoc, giaovien) VALUES ('{$name}', {$sotiet}, '{$row["code"]}', '{$_SESSION["username"]}')");
        $conn->query("UPDATE classroom SET sotiet = sotiet + {$sotiet} WHERE id = {$id}");
        echo swal("Thành công", "Đã thêm buổi học mới!", "success", true);
    }

    if ($action == "delete") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        
        $get = $conn->query("SELECT * FROM `buoihoc` WHERE id = '{$id}'");
        if ($get->num_rows > 0) {
            $row = $get->fetch_array();
            $conn->query("UPDATE classroom SET sotiet = sotiet - {$row["sotiet"]} WHERE code = '{$row["lophoc"]}'");
            $conn->query("DELETE FROM `buoihoc` WHERE id = '{$id}'");
            echo swal("Thành công", "Đã xóa buổi học!", "success", true);    
        } else {
            echo swal("Thất bại", "Buổi học này không tồn tại!", "error", true);    
        }
    }
    
    if ($action == "edit") {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $name = isset($_POST["name"]) ? $_POST["name"] : "";
        $sotiet = isset($_POST["sotiet"]) ? $_POST["sotiet"] : "";
        
        $get = $conn->query("SELECT * FROM `buoihoc` WHERE id = '{$id}'");
        if ($get->num_rows > 0) {
            $row = $get->fetch_array();
            $conn->query("UPDATE classroom SET sotiet = sotiet - {$row["sotiet"]} + {$sotiet} WHERE code = '{$row["lophoc"]}'");
            $conn->query("UPDATE buoihoc SET sotiet = {$sotiet}, name = '{$name}'  WHERE id = '{$id}'");
            echo swal("Thành công", "Đã sửa thông tin buổi học!", "success", true);    
        } else {
            echo swal("Thất bại", "Buổi học này không tồn tại!", "error", true);    
        }
    }
    

    if ($action == "addstudent") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        $code = isset($_GET["code"]) ? $_GET["code"] : "";
        
        $c = $conn->query("SELECT * FROM classroom WHERE code = '{$code}'");
        if ($c->num_rows <= 0) {
            return;
        } else {
            $row = $c->fetch_array();
        }
        $get = $conn->query("SELECT * FROM `students` WHERE id = '{$id}'");
        
        if ($get->num_rows > 0) {
            $check = $conn->query("SELECT * FROM `class_student` WHERE studyid = '{$id}' AND lophoc = '{$code}'");
            if ($check->num_rows <= 0) {
                $conn->query("INSERT INTO `class_student`(studyid, tietdahoc, giaovien, lophoc) VALUES ({$id}, '[]', '{$row["giaovien"]}', '{$code}')");
            }
        }
    }

    if ($action == "comat") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        $buoi = isset($_GET["code"]) ? $_GET["code"] : "";
        
        $get = $conn->query("SELECT * FROM class_student WHERE id = '{$id}'");
        
        if ($get->num_rows > 0) {
            $row = $get->fetch_array();
            $tiethoc = json_decode($row["tietdahoc"], true);
            if (!in_array($buoi, $tiethoc)) {
                array_push($tiethoc, $buoi);
            }
            $tiethoc = json_encode($tiethoc);
            $conn->query("UPDATE `class_student` SET tietdahoc = '{$tiethoc}' WHERE id = {$id}");
        }
    }

    if ($action == "vangmat") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        $buoi = isset($_GET["code"]) ? $_GET["code"] : "";
        
        $get = $conn->query("SELECT * FROM class_student WHERE id = '{$id}'");
        
        if ($get->num_rows > 0) {
            $row = $get->fetch_array();
            $tiethoc = json_decode($row["tietdahoc"], true);
            if (in_array($buoi, $tiethoc)) {
                if (($key = array_search($buoi, $tiethoc)) !== false) {
                    unset($tiethoc[$key]);
                }
            }
            // Chuyển về dạng array list thay vì dict
            $n_l = array();
            foreach($tiethoc as $tiet) {
                array_push($n_l, $tiet);
            }
            $tiethoc = json_encode($n_l);
            $conn->query("UPDATE `class_student` SET tietdahoc = '{$tiethoc}' WHERE id = {$id}");
        }
    }

    if ($action == "removestudent") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        $code = isset($_GET["code"]) ? $_GET["code"] : "";
        $check = $conn->query("SELECT * FROM `class_student` WHERE studyid = {$id} AND lophoc = '{$code}'");
        if ($check->num_rows > 0) {
            $conn->query("DELETE FROM `class_student` WHERE studyid = {$id} AND lophoc = '{$code}'");
        }
    }


?>