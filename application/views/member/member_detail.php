<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<?php
if (! isset($member))
	$member = '';
?>

<div class="container">
 
 	<div class="col-md-8 col-md-offset-2">
 		
 		<form class="form-horizontal" method="post" action="<?=CONTEXT.'/member/detail'?>" id="formMember" onSubmit="return checkSubmit()">
	 		<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="text-center">
						<h4>회원 정보</h4>
					</div>
				</div>
				<div class="panel-body">
					<input type="hidden" id="id" name="ID" value="<?=set_array_value($member, 'ID')?>">
					<div class="form-group">
						<label class="control-label col-xs-2"  for="account">아이디:</label>
						<div class="col-xs-10">
							<input type="text" class="form-control" id="account" name="ACCOUNT" 
										value="<?=set_array_value($member, 'ACCOUNT')?>" placeholder="계정명">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"  for="password">비밀번호:</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password" name="PASSWORD" 
										value="<?=set_array_value($member, 'PASSWORD')?>" placeholder="비밀번호">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="nickname">별명:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nickname" name="NICKNAME" 
									value="<?=set_array_value($member, 'NICKNAME')?>" placeholder="별명">
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">이메일:</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="EMAIL" 
								value="<?=set_array_value($member, 'EMAIL')?>" placeholder="이메일"
								onblur="checkEmail(this)">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">전화번호:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="tel" name="TEL" 
								value="<?=set_array_value($member, 'TEL')?>" placeholder="전화번호"
								onblur="checkTel(this)">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="birthday">생년월일:</label>
						<div class="col-sm-10">
							<div class="form-inline">
								<select class="form-control" name="BIRTH_YEAR">
									<?php
									for ($i = 1950; $i < 2010; $i++) {
									?>
										<option value='<?=$i?>' <?=set_array_select($member, 'BIRTH_YEAR', $i)?>><?=$i?></option>
									<?php
									}
									?>
								</select>
								년
								<select class="form-control" name="BIRTH_MONTH">
									<?php
									for ($i = 1; $i <= 12; $i++) {
									?>
										<option value='<?=$i?>' <?=set_array_select($member, 'BIRTH_MONTH', $i)?>><?=$i?></option>
									<?php
									}
									?>
									
								</select>
								월
								<select class="form-control" name="BIRTH_DAY">
									<?php
									for ($i = 1; $i <= 31; $i++) {
									?>
										<option value='<?=$i?>' <?=set_array_select($member, 'BIRTH_DAY', $i)?>><?=$i?></option>
									<?php
									}
									?>
								</select>
								일
							</div>
						</div>		
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="birthday">주소:</label>
						<div class="col-sm-10">
							<div class="form-inline">
								<button type="button" class="form-control btn btn-warning" 
										onclick="execDaumPostcode(ZIPCODE, ADDRESS1, ADDRESS2)">
									우편번호찾기
								</button>
								<input type="text" class="form-control" id="zipcode" name="ZIPCODE" 
										value="<?=set_array_value($member, 'ZIPCODE')?>" placeholder="우편번호">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="row">
								<div class="col-sm-8">
									<input type="text" class="form-control" id="address1" name="ADDRESS1" 
											value="<?=set_array_value($member, 'ADDRESS1')?>" placeholder="주소">
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="address2" name="ADDRESS2" 
											value="<?=set_array_value($member, 'ADDRESS2')?>" placeholder="상세주소">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="EMAIL_CONFIRM" value="1" 
										<?=set_array_checkbox($member, 'EMAIL_CONFIRM', $i)?>>
									이메일 수신 동의
								</label>
							</div>
						</div>
					</div>
				
				</div>
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">수정</button>
							<button type="button" class="btn btn-danger" id="delete">회원탈퇴</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>

function checkSubmit() {
	var frm = $("#formMember")[0];
	
	if (! checkMemberForm(frm))
		return false;
	
	return true;
	
}

	
function confirmPassword(password) {
	var frm = $("#formMember");

	if (password == '')
		return;
	
	setTimeout(function() {
		$.ajax({
			url: '<?=CONTEXT.'/member/confirm_password'?>', 
			type: 'post',
			data: {
				account : $("#account").val(),
				password : password,
			}, 
			success: function(data, status) {
				if (data.result == "success") {
					frm.attr("action", "<?=CONTEXT.'/member/delete'?>");
					frm.submit();
				}
				else {
					openModalError("에러", data.reason);
				}
	        }
	    })
	}, 1000);
}


$("#delete").on("click", function(event) {
	var frm = $("#formMember");

	openModalInput1("비밀번호 확인", "비밀번호", confirmPassword);

	// after Id is check, do this.
	/*
	frm.attr("action", "memberDelete.do");
	frm.submit();	
	*/
})



</script>

