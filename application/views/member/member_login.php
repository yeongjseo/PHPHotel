
<div class="container">

	<br/><br/>
	<div class="login-box">
		<div class="login-logo">
			<a href="index.do"><b>PHP 호텔</b></a>
		</div>
		<div class="login-box-body">
			<form>
				<div class="form-group has-feedback">
					<input type="text" name="account" id="account1" class="form-control" placeholder="아이디" /> 
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" name="password" id="password1" class="form-control" placeholder="비밀번호" /> 
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="checkbox icheck">
							<label> <input type="checkbox" name="useCookie">자동로그인</label>
						</div>
					</div>
					<div class="col-xs-4">
						<button type="button" class="btn btn-primary btn-block btn-flat" id="login1">로그인</button>
					</div>
				</div>
			</form>

			<a href="#">비밀번호분실</a><br>
			<a href="memberJoin.do" class="text-center">가입하기</a>
		</div>
	</div>

</div>



<script>
$('#login1').click(function() {
	if ($('#account1').val() == '') {
		openModalInfo("확인", "아이디를 입력하시오");
		return;
	}
	
	if ($('#password1').val() == '') {
		openModalInfo("확인", "비밀번호를 입력하시오");
		return;
	}
	
	$.post('<?=CONTEXT.'/member/login'?>', 
		{
       		account: $('#account1').val(),
       		password: $('#password1').val(),
       		return_url: $('#return_url').val(),

        },
        function(data, status) {
        	// JSON
			if (data.result == "success") {
				// openModalInfo("알림", data.account + "님 환영합니다.");
				self.location = "<?=$return_url?>";
			}
			else {
				openModalInfo("로그인 실패", data.reason);
			}
        }
	);
})


</script>

