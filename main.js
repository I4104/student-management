// --------> Search and load Classroom
$("#load").load("handler/load/classroom.php");
$("#search").bind("input", function() {
    if ($(this).val() == "" || $(this).val() == null || $(this).val() == " ") {
        $("#load").load("handler/load/classroom.php");
    } else {
        $("#load").load("handler/load/classroom.php?search=" + $(this).val());
    }
});

function edit_user(id) {
    $("#email").val($("#" + id).attr("email"));
    $("#realname").val($("#" + id).attr("realname"));
    $("#phone").val($("#" + id).attr("phone"));
    $("#congtac").val($("#" + id).attr("congtac"));
    $("#rank").val($("#" + id).attr("rank"));
    $('#editusers').modal('show');
}

$("#users").on('submit', function(e) {
    e.preventDefault();
    callajax("handler/execute/users.php?action=edit", $(this).serialize());
});

function del_user(id) {
    swal({
        title: "Bạn chắc chắn ?",
        text: "Bạn ko thể khôi phục khi đã xóa!",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then(function(isConfirm){
        if (isConfirm) {
            $.ajax({
                url: "handler/execute/users.php?action=delete&id=" + id,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    var data = JSON.parse(data);
                    swal(data.title, data.message, data.type).then(function() {
                        if (data.reload === true) {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    });
    
}


// --------> Create, edit and delete classroom
$("#new_class").on('submit', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    $.ajax({
        url: "handler/execute/class.php?action=add",
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            var data = JSON.parse(data);
            swal(data.title, data.message, data.type).then(function() {
                if (data.reload === true) {
                    window.location.reload();
                }
            });
        }
    });
});

$("#edit_class").on('submit', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    $.ajax({
        url: "handler/execute/class.php?action=edit",
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            var data = JSON.parse(data);
            swal(data.title, data.message, data.type).then(function() {
                if (data.reload === true) {
                    window.location.reload();
                }
            });
        }
    });
});

function del(id) {
    swal({
        title: "Bạn chắc chắn ?",
        text: "Bạn ko thể khôi phục khi đã xóa!",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then(function(isConfirm){
        if (isConfirm) {
            $.ajax({
                url: "handler/execute/class.php?action=delete&id=" + id,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    var data = JSON.parse(data);
                    swal(data.title, data.message, data.type).then(function() {
                        if (data.reload === true) {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    });
    
}

// --------> Add and edit buổi học
$("#new_buoi").on("submit", function(e) {
    e.preventDefault();
    callajax("handler/execute/view.php?action=add", $(this).serialize());
});

$("#edit_buoi").on("submit", function(e) {
    e.preventDefault();
    callajax("handler/execute/view.php?action=edit", $(this).serialize());
});

function del_buoi(id) {
    swal({
        title: "Bạn chắc chắn ?",
        text: "Bạn ko thể khôi phục khi đã xóa!",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then(function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: "handler/execute/view.php?action=delete&id=" + id,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    var data = JSON.parse(data);
                    swal(data.title, data.message, data.type).then(function() {
                        if (data.reload === true) {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    });
    
}

// --------> Add students 
$("#new_student").on('submit', function(e) {
    e.preventDefault();
    callajax("handler/execute/students.php?type=basic", $(this).serialize());
});

$("#i_excel").on('submit', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    $.ajax({
        url: "handler/execute/students.php?type=excel",
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            var data = JSON.parse(data);
            swal(data.title, data.message, data.type).then(function() {
                if (data.reload === true) {
                    window.location.reload();
                }
            });
        }
    });
});

function del_student(id) {
    swal({
        title: "Bạn chắc chắn ?",
        text: "Bạn ko thể khôi phục khi đã xóa!",
        icon: "warning",
        dangerMode: true,
        buttons: true,
    }).then(function(isConfirm){
        if (isConfirm) {
            $.ajax({
                url: "handler/execute/students.php?action=delete&id=" + id,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    var data = JSON.parse(data);
                    swal(data.title, data.message, data.type).then(function() {
                        if (data.reload === true) {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    });
    
}


$('[data-action="import-to-class"]').change(function(e){ 
    var id = e.target.id;
    var code = $('[data-action="import-to-class"]').attr("data-class");
    if ($(this).is(":checked")) {
        $.ajax({
            url: "handler/execute/view.php?action=addstudent&id=" + id + "&code="+code,
            type: 'GET'
        });
    } else {
        $.ajax({
            url: "handler/execute/view.php?action=removestudent&id=" + id + "&code="+code,
            type: 'GET'
        });
    }
});

$('[data-action="diem-danh"]').change(function(e){ 
    var code = $('[data-action="diem-danh"]').attr("data-buoi");
    var td = e.target.id;
    if ($(this).is(":checked")) {
        $.ajax({
            url: "handler/execute/view.php?action=comat&id=" + td + "&code="+code,
            type: 'GET'
        });
        $('[data-id="'+ td +'"]').text("Có mặt");
    } else {
        $.ajax({
            url: "handler/execute/view.php?action=vangmat&id=" + td + "&code="+code,
            type: 'GET'
        });
        $('[data-id="'+ td +'"]').text("Vắng mặt");
    }
});

//select all
$('[data-select="checkbox"]').change(function(){
	$target = $(this).attr('data-target');
	$($target).prop('checked', $(this).prop("checked"));
	//$($target).click();
	var target = $('[data-action="import-to-class"]');
	for(var i=0; i < target.length; i++) {
	    var id = $(target)[i].id;
	    var code = $('[data-action="import-to-class"]').attr("data-class");
        if ($(this).prop("checked")) {
            $.ajax({
                url: "handler/execute/view.php?action=addstudent&id=" + id + "&code="+code,
                type: 'GET'
            });
        } else {
            $.ajax({
                url: "handler/execute/view.php?action=removestudent&id=" + id + "&code="+code,
                type: 'GET'
            });
        }
    }
    
    var buoi = $('[data-action="diem-danh"]');
    for(var j=0; j < buoi.length; j++) {
	    var td = $(buoi)[j].id;
	    var code = $('[data-action="diem-danh"]').attr("data-buoi");
        if ($(this).is(":checked")) {
            $.ajax({
                url: "handler/execute/view.php?action=comat&id=" + td + "&code="+code,
                type: 'GET'
            });
            $('[data-id="'+ td +'"]').text("Có mặt");
        } else {
            $.ajax({
                url: "handler/execute/view.php?action=vangmat&id=" + td + "&code="+code,
                type: 'GET'
            });
            $('[data-id="'+ td +'"]').text("Vắng mặt");
        }
    }
})


$("#addstudent").on('hide.bs.modal', function() {
    setTimeout(function(){
        window.location.reload();
    }, 200);
});


// --------> Login, signup, logout, forgot password
var input = $('.validate-input .input');

$('.validate-form').on('submit', function(e) {
    e.preventDefault();
    var check = true;
    for(var i=0; i < input.length; i++) {
        if (validate(input[i]) == false){
            showValidate(input[i]);
            check = false;
        }
    }
    if (check == false) return;
    if ($(this).attr('id') == "login") {
        callajax('handler/execute/users.php?action=login', $(this).serialize());
    }
    if ($(this).attr('id') == "forgot") {
        callajax('handler/execute/users.php?action=forgot', $(this).serialize());
    }
    if ($(this).attr('id') == "signup") {
        if ($("input[name='password']").val() != $("input[name='repassword']").val()) {
            swal("Thất bại", "Nhập lại mật khẩu không chính xác!", "error");
            return;
        }
        callajax('handler/execute/users.php?action=register', $(this).serialize());
    }
    if ($(this).attr('id') == "change") {
        if ($("input[name='password']").val() != $("input[name='repassword']").val()) {
            swal("Thất bại", "Nhập lại mật khẩu không chính xác!", "error");
            return;
        }
        callajax('handler/execute/users.php?action=change', $(this).serialize());
    }
});

$('#forgot').on('submit', function(e) {
    e.preventDefault();
    $("#btn_send").attr("disabled", "true");
    $("#btn_send").text("Đang gửi email");
    callajax('handler/execute/users.php?action=forgot', $(this).serialize());
});

function callajax(url, data) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            console.log(data);
            var data = JSON.parse(data);
            swal(data.title, data.message, data.type).then(function() {
                if (data.reload === true) {
                    window.location.reload();
                }
            });
        }
    });
}

function logout() {
    $.ajax({
        url: "handler/execute/users.php?action=logout",
        type: 'GET',
        success: function(data) {
            window.location.reload();
        }
    });
}



/*==================================================================
[ Xử lý giao diện ] */

$(function () {
	$('[data-toggle="tooltip"]').tooltip();
});

jQuery(document).ready(function(){
    jQuery('.scrollbar-inner').scrollbar();
});

$(document).ready(function(){
	var toggle_sidebar = false,
	toggle_topbar = false,
	nav_open = 0,
	topbar_open = 0;

	if(!toggle_sidebar) {
		$toggle = $('.sidenav-toggler');

		$toggle.click(function() {
			if (nav_open == 1){
				$('html').removeClass('nav_open');
				$toggle.removeClass('toggled');
				nav_open = 0;
			}  else {
				$('html').addClass('nav_open');
				$toggle.addClass('toggled');
				nav_open = 1;
			}
		});
		toggle_sidebar = true;
	}

	if(!toggle_topbar) {
		$topbar = $('.topbar-toggler');

		$topbar.click(function(){
			if (topbar_open == 1) {
				$('html').removeClass('topbar_open');
				$topbar.removeClass('toggled');
				topbar_open = 0;
			} else {
				$('html').addClass('topbar_open');
				$topbar.addClass('toggled');
				topbar_open = 1;
			}
		});
		toggle_topbar = true;
	}
});



/*==================================================================
[ Xử lý form ] */
$('.input').each(function(){
    $(this).on('blur', function(){
        if($(this).val().trim() != "") {
            $(this).addClass('has-val');
        }
        else {
            $(this).removeClass('has-val');
        }
    })    
});

$('.validate-form .input').each(function(){
    $(this).focus(function(){
       hideValidate(this);
    });
});

function validate (input) {
    if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
        if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
            return false;
        }
    } else {
        if($(input).val().trim() == '') {
            return false;
        }
    }
    if($(input).attr('type') == 'phone' || $(input).attr('name') == 'phone') {
        if($(input).val().trim().match(/^([0-9]+)$/) == null) {
            return false;
        }
    } else {
        if($(input).val().trim() == '') {
            return false;
        }
    }
}

function showValidate(input) {
    var thisAlert = $(input).parent();
    $(thisAlert).addClass('alert-validate');
}

function hideValidate(input) {
    var thisAlert = $(input).parent();

    $(thisAlert).removeClass('alert-validate');
}