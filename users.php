<?php include "handler/config.php"; 
    if (!isset($_SESSION["username"])) header("location: login.php");
    $users = $conn->query("SELECT * FROM users WHERE email = '{$_SESSION["username"]}'")->fetch_array();
    if ($rank != "admin") {
        header("location: login.php");
    }
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
									<a class="dropdown-item" href="#"><i class="ti-user"></i> My Profile</a>
									<a class="dropdown-item" href="#"><i class="ti-settings"></i> Account Setting</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#"><i class="fa fa-power-off"></i> Logout</a>
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
						<li class="nav-item">
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
						<li class="nav-item active">
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
    					<h4 class="page-title">Danh sách học sinh</h4>
    					<div class="row">
							<div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<div class="card-title">Danh sách người dùng</div>
    									<div class="card-category">Danh sách toàn bộ người dùng website</div>
    								</div>
    								<div class="card-body table-responsive">
    									<table class="table table-bordered">
											<thead>
											    <th>ID</th>
												<th>Tên</th>
												<th>Email</th>
												<th>Rank</th>
												<th>Phone</th>
												<th>Công tác</th>
												<th>Sửa</th>
												<th>Xóa</th>
											</thead>
											<tbody>
											    <?php 
											        $students = $conn->query("SELECT * FROM users");
											        
											        if ($students->num_rows > 0) {
											            $i = 0;
											            while ($row = $students->fetch_array()) {
											                $i++;
											                $data = 'realname="'. $row['realname'] .'" email="'. $row['email'] .'" rank="'. $row['rank'] .'" phone="'. $row['phone'] .'" congtac="'. $row['congtac'] .'"';
											                echo '
											                    <tr>
                													<td>'.$i.'</td>
                													<td>'. $row['realname'] .'</td>
                													<td>'. $row['email'] .'</td>
                													<td>'. $row['rank'] .'</td>
                													<td>'. $row['phone'] .'</td>
                													<td>'. $row['congtac'] .'</td>
                													<td class="text-center width-60px"><a data-toggle="tooltip" title="Sửa thông tin" id='. $row["id"] .' '. $data .' class="text-danger" onclick="edit_user(this.id);"><i class="fa fa-edit"></i></a></td>
                													<td class="text-center width-60px"><a data-toggle="tooltip" title="Xóa người dùng?" class="text-danger" onclick="del_user('. $row["id"] .')"><i class="fa fa-trash"></i></a></td>
                												</tr>
											                
											                ';
											            }
											        } else {
											            echo '<td colspan="7">Không có dữ liệu</td>';        
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
	
	<div class="modal fade" id="editusers" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Sửa thông tin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="users">
                        <div class="form-group">
                            <label>Tên thật</label>
                            <input type="text" class="form-control shadow-none" name="realname" id="realname" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control shadow-none" name="email" id="email" readonly>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" class="form-control shadow-none" name="phone" id="phone" required>
                        </div>
                        <div class="form-group">
                            <label>Công tác</label>
                            <input type="text" class="form-control shadow-none" name="congtac" id="congtac" required>
                        </div>
                        <div class="form-group">
                            <label>Loại tài khoản</label>
                            <select class="form-control shadow-none" name="rank" id="rank">
                                <option value="default">Thường</option>
                                <option value="admin">ADMIN</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
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