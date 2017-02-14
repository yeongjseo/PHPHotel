<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.min.css">
	
	<link rel="stylesheet" href="<?=CONTEXT.'/static/css/common.css'?>">
	<link href="<?=CONTEXT.'/static/img/icon/favicon.ico'?>" rel="shortcut icon" type="image/x-icon"/>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.1/handlebars.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/js/app.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/js/demo.js" type="text/javascript"></script>
	
		
	<script src="<?=CONTEXT.'/static/js/common.js'?>"></script>
	

	<title>PHP 호텔</title>
</head>

<div id="modalInfo" class="modal modal-primary fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" data-rno>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>

<div id="modalDanger" class="modal modal-primary fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" data-rno>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>

  
<input type="hidden" id="modalConfirmInput"> 
<div id="modalConfirm" class="modal modal-primary fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" data-rno>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" id="modalConfirmYes" data-dismiss="modal">확인</button>
        		<button type="button" class="btn btn-danger" id="modalConfirmNo" data-dismiss="modal">취소</button>
			</div>
		</div>
	</div>
</div>  

<div id="modalNoClose" class="modal modal-primary fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" data-rno>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

<div id="modalInput1" class="modal modal-primary fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" data-rno>
				<form class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-2" id="modalInput1Label" ></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="modalInput1Text"/>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" id="modalInput1Btn" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>  


<form id="commonForm" name="commonForm"></form>

<script>


function openModalInfo(title, body) {
	var box = $("#modalInfo");
	box.find(".modal-title").html(title);
	box.find(".modal-body").html(body);
	box.modal();
}

function openModalError(title, body) {
	var box = $("#modalDanger");
	box.find(".modal-title").html(title);
	box.find(".modal-body").html(body);
	box.modal();
}


function openModalConfirm(title, body) {
	var box = $("#modalConfirm");
	box.find(".modal-title").html(title);
	box.find(".modal-body").html(body);
	box.modal();
}
$("#modalConfirmYes").on('click', function () {    
	$("modalConfirmInput").val("yes");
});

$("#modalConfirmNo").on('click', function () {    
	$("modalConfirmInput").val("no");   
});

function getModalConfirm() {
	return $("modalConfirmInput").val();
}


function modalNoClose(title, body) {
	var box = $("#modalNoClose");
	box.find(".modal-title").html(title);
	box.find(".modal-body").html(body);
	box.modal();
}
function closeModalNoClose() {
	var box = $("#modalNoClose");
	box.modal('hide');
}


function openModalInput1(title, body, cb) {
	var modailInput1Callback;
	var box = $("#modalInput1");
	box.modal('hide');
	box.find(".modal-title").html(title);
	box.find("#modalInput1Label").html(body);
	box.find("#modalInput1Text").val("");
	box.modal();
	
	modailInput1Callback = cb;
	$('#modalInput1').on('hidden.bs.modal', function () {    
		modailInput1Callback($("#modalInput1Text").val());
		return true;
	})
}




$(document).ready(function() {
	$.fn.datepicker.dates['ko'] = {
	    days: ["일", "월", "화", "수", "목", "금", "토"],
	    daysShort: ["일", "월", "화", "수", "목", "금", "토"],
	    daysMin: ["일", "월", "화", "수", "목", "금", "토"],
	    months: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
	    monthsShort: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
	    today: "오늘",
	    clear: "취소",
	    format: "yyyy/mm/dd",
	    titleFormat: "yyyy년 mm",
	    weekStart: 0
	};
})


$(document).ready(function() {

	var msg = '${messageDO.result}';
	if (msg == 'error') {
		openModalError("알림", '${messageDO.reason}');
	}
	else if (msg == 'login') {
		openModalInfo("로그인", '${messageDO.reason}');
	}
	else if (msg == 'logout') {
		openModalInfo("로그아웃", '${messageDO.reason}');
	}

	<?php
	if (($msg = $this->session->flashdata('message'))) {
		$result = $msg['result'];
		$reason = $msg['reason'];
		if ($msg['result'] == 'info')
			echo "openModalInfo('알림', '{$reason}');";
		if ($msg['result'] == 'error')
			echo "openModalError('에러', '{$reason}');";

	}
	?>
	
});



</script>
	