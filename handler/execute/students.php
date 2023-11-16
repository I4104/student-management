<?php
    include "../config.php";
    require "../class/Classes/PHPExcel.php";
    include '../class/Classes/PHPExcel/IOFactory.php';
    
    $type = isset($_GET["type"]) ? $_GET["type"] : "";

    if ($type == "basic") {
        $mahocsinh = isset($_POST["mahocsinh"]) ? $_POST["mahocsinh"] : "";
        $ho = isset($_POST["ho"]) ? $_POST["ho"] : "";
        $ten = isset($_POST["ten"]) ? $_POST["ten"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        
        $get = $conn->query("SELECT * FROM students WHERE giaovien = '{$_SESSION["username"]}' AND mahocsinh = '{$mahocsinh}'");
        if ($get->num_rows > 0) {
            echo swal("Thất bại", "Mã học sinh này đã tồn tại trong lớp", "error", false);
        } else {
            $conn->query("INSERT INTO `students`(`mahocsinh`, `ho`, `ten`, `email`, `giaovien`) VALUES ('{$mahocsinh}', '{$ho}', '{$ten}', '{$email}', '{$_SESSION["username"]}')");
            echo swal("Thành công", "Đã thêm học sinh vào lớp", "success", true);
        }
    }
    
    if ($type == "excel") {
        $file = $_FILES['excel']['tmp_name'];
        
        // Kiểm tra load file
        $allow = array("csv", "xls", "xlsx");
        list($name, $ext) = explode('.', $_FILES['excel']['name']);
        if (!in_array($ext, $allow)) {
            echo swal("Thất bại", "File bạn nhập không đúng định dạng", "error", false);
            return;
        }
        
        $objFile = PHPExcel_IOFactory::identify($file);
        $objData = PHPExcel_IOFactory::createReader($objFile);
        //Chỉ đọc dữ liệu
        $objData->setReadDataOnly(true);
        
        // Load dữ liệu sang dạng đối tượng
        $objPHPExcel = $objData->load($file);
        
        //Lấy ra số trang sử dụng phương thức getSheetCount();
        //Lấy Ra tên trang sử dụng getSheetNames();
        
        //Chọn trang cần truy xuất
        $sheet  = $objPHPExcel->setActiveSheetIndex(0);
        
        //Lấy ra số dòng cuối cùng
        $Totalrow = $sheet->getHighestRow();
        //Lấy ra tên cột cuối cùng
        $LastColumn = $sheet->getHighestColumn();
        
        //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
        $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
        
        //Tạo mảng chứa dữ liệu
        $data = [];
        
        //Tiến hành lặp qua từng ô dữ liệu
        //----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
        for ($i = 2; $i <= $Totalrow; $i++)
        {
        	//----Lặp cột
        	for ($j = 0; $j < $TotalCol; $j++)
        	{
            	// Tiến hành lấy giá trị của từng ô đổ vào mảng
        		$data[$i-2][$j]=$sheet->getCellByColumnAndRow($j, $i)->getValue();
        	}
        }
        $get = $conn->query("SELECT * FROM `students` WHERE giaovien = '{$_SESSION["username"]}'");
        if ($get->num_rows > 0) {
            while ($row = $get->fetch_array()) {
                $conn->query("DELETE FROM class_student WHERE studyid = '{$row["id"]}'");
            }    
        }
        
        $conn->query("DELETE FROM students WHERE giaovien = '{$_SESSION["username"]}'");
        foreach($data as $item) {
            $conn->query("INSERT INTO `students`(`mahocsinh`, `ho`, `ten`, `email`, `giaovien`) VALUES ('{$item[0]}', '{$item[1]}', '{$item[2]}', '{$item[3]}', '{$_SESSION["username"]}')");
        }
        echo swal("Thành công", "Đã thêm danh sách học sinh vào lớp", "success", true);
    }
    
    if ($type == "delete") {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        
        $get = $conn->query("SELECT * FROM `students` WHERE id = '{$id}'");
        if ($get->num_rows > 0) {
            $conn->query("DELETE FROM `students` WHERE id = '{$id}'");
            $conn->query("DELETE FROM class_student WHERE studyid = '{$id}'");
            echo swal("Thành công", "Đã xóa học sinh!", "success", true);    
        } else {
            echo swal("Thất bại", "Học sinh này không tồn tại!", "error", true);    
        }
    }

?>