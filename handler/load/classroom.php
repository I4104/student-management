<?php 
    include "../config.php";
    $search = isset($_GET["search"]) ? "WHERE tenlop LIKE '%". $_GET["search"]. "%' OR monhoc LIKE '%". $_GET["search"]. "%' OR phonghoc LIKE '%". $_GET["search"] . "%'" : "";
    $get = $conn->query("SELECT * FROM classroom {$search}");    
    
    if ($get->num_rows > 0) {
        while($row = $get->fetch_array()) {
            if ($rank == "admin") {
                $count = $conn->query("SELECT * FROM class_student WHERE lophoc = '{$row["code"]}'")->num_rows;
                echo '
                    <div class="col-md-4">
    					<div class="col-md-12">
    						<div class="card">
    							<div class="card-header">
    								<div class="card-title">'. $row["tenlop"] .'</div>
    								<div class="card-category">Môn học: '. $row["monhoc"] .'</div>
    							</div>
    							<div class="card-body">
    								<div class="row">
    								    <div class="col">
    								        <div class="col">
    								            <img src="resource/class/'. $row["image"] .'" style="width: 100px; height: 100px;">
    								        </div>
    								    </div>
    								    <div class="col info">
    								        <p>Phòng học: '. $row["phonghoc"] .'</p>
    							            <p>Sĩ số: '. $count .'</p>
    							            <p>Số tiết: '. $row["sotiet"] .'</p>
    								    </div>
    								</div>
    							</div>
    							<div class="card-footer">
    								<a class="btn btn-success width-60" href="view.php?id='. $row["id"] .'">Xem ngay</a>
    								<a class="btn btn-danger width-38" href="javascript:void(0);" onclick="del('. $row["id"] .')">Xóa</a>
    							</div>
    						</div>
    					</div>
    				</div>
                ';
            } else {
                if ($row["giaovien"] == $_SESSION["username"]) {
                    $count = $conn->query("SELECT * FROM class_student WHERE lophoc = '{$row["code"]}'")->num_rows;
                    echo '
                        <div class="col-md-4">
        					<div class="col-md-12">
        						<div class="card">
        							<div class="card-header">
        								<div class="card-title">'. $row["tenlop"] .'</div>
        								<div class="card-category">Môn học: '. $row["monhoc"] .'</div>
        							</div>
        							<div class="card-body">
        								<div class="row">
        								    <div class="col">
        								        <div class="col">
        								            <img src="resource/class/'. $row["image"] .'" style="width: 100px; height: 100px;">
        								        </div>
        								    </div>
        								    <div class="col info">
        								        <p>Phòng học: '. $row["phonghoc"] .'</p>
        							            <p>Sĩ số: '. $count .'</p>
        							            <p>Số tiết: '. $row["sotiet"] .'</p>
        								    </div>
        								</div>
        							</div>
        							<div class="card-footer">
        								<a class="btn btn-success width-60" href="view.php?id='. $row["id"] .'">Xem ngay</a>
        								<a class="btn btn-danger width-38" href="javascript:void(0);" onclick="del('. $row["id"] .')">Xóa</a>
        							</div>
        						</div>
        					</div>
        				</div>
                    ';
                }
            }
            
        }
    } else {
        echo '<p>Bạn chưa có lớp nào</p>';
    }
?>