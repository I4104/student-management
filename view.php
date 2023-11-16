<?php include "handler/config.php"; 
    if (!isset($_SESSION["username"])) header("location: login.php");
    
    if (!isset($_GET["id"])) {
        header("location: classroom.php");
    }
    $get = $conn->query("SELECT * FROM classroom WHERE id = '{$_GET["id"]}'");
    if ($get->num_rows > 0) {
        $class = $get->fetch_array();
    } else {
        header("location: classroom.php");
    }
    $code = $class["code"];
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Quản lý học sinh, sinh viên</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.4/chartist.min.css" integrity="sha512-V0+DPzYyLzIiMiWCg3nNdY+NyIiK9bED/T1xNBj08CaIUyK3sXRpB26OUCIzujMevxY9TRJFHQIxTwgzb0jVLg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<div class="logo-header">
				<a href="index.php" class="logo">
					I4104
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<button class="topbar-toggler more"><i class="fa fa-user"></i></button>
			</div>
			<nav class="navbar navbar-header navbar-expand-lg">
				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item dropdown">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false"> <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSeIUUwf1GuV6YhA08a9haUQBOBRqJinQCJxA&usqp=CAU" alt="user-img" width="36" class="img-circle"><span ><?php echo $users["realname"]; ?></span></span> </a>
							<ul class="dropdown-menu dropdown-user">
								<li>
									<div class="user-box">
										<div class="u-img"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSeIUUwf1GuV6YhA08a9haUQBOBRqJinQCJxA&usqp=CAU" alt="user"></div>
										<div class="u-text">
											<h4><?php echo $users["realname"]; ?></h4>
										</div>
									</li>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#"><i class="ti-settings"></i> Account Setting</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#" onclick="logout();"><i class="fa fa-power-off"></i> Logout</a>
								</ul>
								<!-- /.dropdown-user -->
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<div class="sidebar">
				<div class="scrollbar-inner sidebar-wrapper">
					<ul class="nav">
						<li class="nav-item active">
							<a href="classroom.php">
								<i class="fa fa-chalkboard-teacher"></i>
								<p>Lớp học</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="students.php">
								<i class="fa fa-users"></i>
								<p>Học sinh</p>
							</a>
						</li>
						<?php if ($rank == "admin") { ?>
						<li class="nav-item">
							<a href="users.php">
								<i class="fa fa-users"></i>
								<p>Người dùng</p>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="main-panel">
				<div class="content">
					<div class="container-fluid">
    					<h4 class="page-title">Lớp học: <?php echo $class["tenlop"]; ?></h4>
    					<div class="row">
							<div class="col-lg-12">
								<div class="card">
								    <div class="card-header">
										<h4 class="card-title">Thông tin cơ bản</h4>
										<p class="card-category">Chỉnh sửa thông tin lớp học</p>
									</div>
									<div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <form id="edit_class" method="POST" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Tên lớp học:</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="text" name="tenlop" value="<?php echo $class["tenlop"]; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Tổng số tiết học:</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" min="1" name="sotiet" value="<?php echo $class["sotiet"]; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">ID lớp học:</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="text" name="id" value="<?php echo $class["id"]; ?>" readonly>
                                                        </div>
                                                    </div>
        									    </div>
        									    <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Môn học:</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="text" name="monhoc" value="<?php echo $class["monhoc"]; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Phòng học:</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="text" name="phonghoc" value="<?php echo $class["phonghoc"]; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Ảnh đại diện:</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control-file" type="file" name="img">
                                                        </div>
                                                    </div>
        									        <button type="submit" class="btn btn-success float-right">Lưu</button>
								                </form>
    									    </div>
									    </div>
									</div>
								</div>
							</div>
						</div>
    					<div class="row">
							<div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    								    <button class="btn btn-success float-right" data-toggle="modal" data-target="#addbuoi">Thêm mới</button>
    									<div class="card-title">Lớp học: <?php echo $class["tenlop"]; ?></div>
    									<div class="card-category">Danh sách buổi học</div>
    								</div>
    								<div class="card-body table-responsive">
    									<table class="table table-bordered">
											<thead>
											    <th>ID</th>
												<th>Tên buổi học</th>
												<th>Số tiết</th>
												<th>Xóa</th>
											</thead>
											<tbody>
											    <?php
											        $buoihoc = $conn->query("SELECT * FROM buoihoc WHERE giaovien = '{$class["giaovien"]}' AND  lophoc = '{$class["code"]}'");
                                                    if ($buoihoc->num_rows > 0) {
                                                        $i=0;
                                                        while($row = $buoihoc->fetch_array()) {
                                                            $i++;
                                                            echo '
                                                                <tr>
                													<td>'.$i.'</td>
                													<td><a class="text-success" href="lesson.php?id='. $row["id"] .'" data-toggle="tooltip" title="Click vào để xem chi tiết">'. $row["name"] .'</a></td>
                													<td>'. $row["sotiet"] .'</td>
                													<td class="text-center width-60px"><a data-toggle="tooltip" href="javascript:void(0);" title="Xóa buổi học?" class="text-danger" onclick="del_buoi('. $row["id"] .')"><i class="fa fa-trash"></i></a></td>
                												</tr>
                                                            ';
                                                        }
                                                    } else {
                                                        echo '<td colspan="4" class="text-center">Không có buổi học nào!</td>';
                                                    }
											    ?>
											</tbody>
										</table>
									</div>
    							</div>
    						</div>
    					</div>
    					<div class="row">
							<div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    								    <button class="btn btn-success float-right" data-toggle="modal" data-target="#addstudent">Thêm hoặc xóa</button>
    									<div class="card-title">Lớp học: <?php echo $class["tenlop"]; ?></div>
    									<div class="card-category">Danh sách học sinh</div>
    								</div>
    								<div class="card-body table-responsive">
    									<table class="table table-bordered">
											<thead>
											    <th>ID</th>
												<th>Họ và tên lót</th>
												<th>Tên học sinh</th>
												<th>Email</th>
												<th>Số tiết vắng</th>
											</thead>
											<tbody>
											    <?php
											        $get = $conn->query("SELECT * FROM class_student WHERE giaovien = '{$class["giaovien"]}' AND  lophoc = '{$class["code"]}'");
											        
											        if ($get->num_rows > 0) {
											            $i = 0;
											            while ($row = $get->fetch_array()) {
											                $vang = 0;
											                $tiethoc = json_decode($row["tietdahoc"], true);
											                $buoihoc = $conn->query("SELECT * FROM buoihoc WHERE giaovien = '{$class["giaovien"]}' AND  lophoc = '{$class["code"]}'");
											                if ($buoihoc->num_rows > 0) {
											                    while ($buoi = $buoihoc->fetch_array()) {
											                        if (!in_array($buoi["id"], $tiethoc)) {
											                            $vang = $vang + $buoi["sotiet"];
											                        }
											                    }
											                }
											                
											                $i++;
											                $student = $conn->query("SELECT * FROM students WHERE id = '{$row["studyid"]}'")->fetch_array();
											                echo '
											                    <tr>
                													<td>'. $i .'</td>
                													<td>'. $student["ho"] .'</td>
                													<td>'. $student["ten"] .'</td>
                													<td>'. $student["email"] .'</td>
                													<td>'. $vang .'</td>
                												</tr>
											                ';
											            } 
											        } else {
											                echo '<td colspan="5" class="text-center">Chưa thêm học sinh nào vào lớp!</td>';
											        }
											    ?>
											</tbody>
										</table>
									</div>
    							</div>
    						</div>
						</div>
    				</div>
				</div>
			</div>
		</div>
	</div>
</body>
<div class="modal fade" id="addbuoi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Thêm buổi học mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="new_buoi">
                    <div class="form-group">
                        <label>ID Lớp học</label>
                        <input type="text" class="form-control shadow-none" name="id" value="<?php echo $class["id"]; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tên buổi học</label>
                        <input type="text" class="form-control shadow-none" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Số tiết</label>
                        <input type="number" class="form-control shadow-none" name="sotiet" required>
                    </div> 
                    <button type="submit" class="btn btn-success float-right">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addstudent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-lg modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Chọn học sinh:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
					<table class="table table-bordered">
						<thead>
						    <th class="width-60px">
								<div class="form-check">
									<label class="form-check-label" style="padding: 0">
									    <input class="form-check-input select-all-checkbox" data-action="check-all" type="checkbox" data-select="checkbox" data-target=".task-select">
										<span class="form-check-sign"></span>
									</label>
								</div>
							</th>
							<th>Họ và tên lót</th>
							<th>Tên học sinh</th>
						</thead>
						<tbody>
						    <?php
						        $get = $conn->query("SELECT * FROM students WHERE giaovien = '{$class["giaovien"]}'");
						        
						        if ($get->num_rows > 0) {
						            while($row = $get->fetch_array()) {
						                $check = $conn->query("SELECT * FROM class_student WHERE studyid = '{$row["id"]}' AND lophoc = '{$class["code"]}'");
										if ($check->num_rows > 0) {
						                    $checked = "checked";
						                } else {
						                    $checked = "";
						                }
						                echo '
						                    <tr>
                								<td>
                									<div class="form-check">
                										<label class="form-check-label">
                											<input class="form-check-input task-select" data-action="import-to-class" data-class="'. $code .'" type="checkbox" id="'. $row["id"] .'" '. $checked .'>
                											<span class="form-check-sign"></span>
                										</label>
                									</div>
                								</td>
                								<td>'. $row["ho"] .'</td>
                								<td>'. $row["ten"] .'</td>
                							</tr>
						                ';
						            }        
						        }
						    ?>
						</tbody>
					</table>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.4/chartist.min.js" integrity="sha512-9rxMbTkN9JcgG5euudGbdIbhFZ7KGyAuVomdQDI9qXfPply9BJh0iqA7E/moLCatH2JD4xBGHwV6ezBkCpnjRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartist-plugin-tooltip/0.0.11/chartist-plugin-tooltip.js" integrity="sha512-BuKBs9rG452zZGqNg7QvRrgX/2zvxe09OeyE7e8bg9XPM7MU0nnu5YGeZDX91vMOTfE88eFIDQ0/OzGZaoecvw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js" integrity="sha512-vCgNjt5lPWUyLz/tC5GbiUanXtLX1tlPXVFaX5KAQrUHjwPcCwwPOLn34YBFqws7a7+62h7FRvQ1T0i/yFqANA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-toggle/3.6.1/bootstrap4-toggle.min.js" integrity="sha512-bAjB1exAvX02w2izu+Oy4J96kEr1WOkG6nRRlCtOSQ0XujDtmAstq5ytbeIxZKuT9G+KzBmNq5d23D6bkGo8Kg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mapael/2.2.0/js/jquery.mapael.min.js" integrity="sha512-+iXNzFArGbqxdmbClb1f6MKIiZASR7H8ep6rS1ZFn2I7tRX400ApvS0nsG8/v1+F7RoGU2shMDTl/gZ5lZF1iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mapael/2.2.0/js/maps/world_countries.min.js" integrity="sha512-QGmaAYAgVbqkUFtLzXmKhaP52gAePFwe50bNkE0SbflQ4sm6mmdvufVnKnb5CNgRP2nW4ondofrZ++1dTEAJ4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/circles/0.0.6/circles.min.js" integrity="sha512-r1w3tnPCKov9Spj2bJGCQQBJ5wcJywFgL79lKMXvzBMXIPFI9xXQDmwuVs+ERh1tnL0UFT1hLrwtKh1z5/XCCQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.scrollbar/0.2.11/jquery.scrollbar.min.js" integrity="sha512-5AcaBUUUU/lxSEeEcruOIghqABnXF8TWqdIDXBZ2SNEtrTGvD408W/ShtKZf0JNjQUfOiRBJP+yHk6Ab2eFw3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="main.js"></script>
</html>