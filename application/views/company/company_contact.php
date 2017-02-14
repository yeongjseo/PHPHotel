<div class="container">
 	<div class="col-sm-10 col-sm-offset-1">
		<form class="form-horizontal" method="post" id="form1">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="text-center">
						<h4>문의하기</h4>
					</div>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-sm-2" for="name">이름:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="name" 
								value="<?=set_value('name')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">이메일:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="email"
								value="<?=set_value('email')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">전화번호:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="tel" 
								value="<?=set_value('tel')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">제목:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="title" 
								value="<?=set_value('title')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">내용:</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="content" rows="10"><?=set_value('content')?></textarea>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="button"  class="btn btn-info" id="send">메일 전송하기</button>
						</div>
					</div>
				</div>	
			</div>
		</form>
	</div>
</div>


<script>

$(document).ajaxStart(function () {
	modalNoClose("알림", "메일을 전송중입니다...");	
});

$(document).ajaxStop(function () {
	// closeModalNoClose();
});



$("#send").on("click", function(event) {
	var frm = $("#form1");
	
	if (form1.name.value == "") {
		openModalInfo("알림", "이름을 입력하세요.")
		form1.name.focus();
		return;
	}
	
	if (! checkEmail(form1.email)) {
		openModalInfo("알림", "이메일주소을 다시 입력하세요.")
		form1.email.focus();
		return;
	}
	
	if (! checkTel(form1.tel)) {
		openModalInfo("알림", "전화번호를 다시 입력하세요.")
		form1.tel.focus();
		return;
	
	}
	if (form1.content.value == "") {
		openModalInfo("알림", "내용을 입력하세요.")
		form1.email.focus();
		return;
	}
	
	
	// frm.submit();
	/* OK
	$.post('companyContact.do', frm.serialize(), function(data, status) {
			if (data.result == "success") {
				openModalInfo("알림", "메일을 전송하였습니다.");
			}
			else {
				openModalInfo("실패", data.reason);
			}
        }
	);
	*/
	
	// OK
	$.ajax({
		url: '<?=CONTEXT.'/company/contact'?>', 
		type: 'post',
		// Form Data
		// 		name=seo&email=ssss%40sss.com&tel=01011111111&title=d&content=d
		data: frm.serialize(), 
		success: function(data, status) {
			if (data.result == "success") {
				closeModalNoClose();
				openModalInfo("알림", "메일을 전송하였습니다.");
			}
			else {
				closeModalNoClose();
				openModalInfo("실패", data.reason);
			}
        }
    })
	
	
	

})
</script>
