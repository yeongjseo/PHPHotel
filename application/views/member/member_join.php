<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<?php 
if (! isset($birth_year)) {
	$birth_year = 1980;
	$birth_month = 2;
	$birth_day = 2;
}
?>

<div class="container">
 
 	<div class="col-md-8 col-md-offset-2">
 		
 		<form class="form-horizontal" method="post" action="<?=CONTEXT.'/member/join'?>" id="formJoin">
	 		<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="text-center">
						<h4>회원 정보</h4>
					</div>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-sm-2"  for="account">아이디:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="ACCOUNT" value="<?=set_value('ACCOUNT')?>" placeholder="아이디">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"  for="password">비밀번호:</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="PASSWORD" value="<?=set_value('PASSWORD')?>" placeholder="비밀번호">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="nickname">별명:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="NICKNAME" value="<?=set_value('NICKNAME')?>" placeholder="별명">
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">이메일:</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" name="EMAIL" value="<?=set_value('EMAIL')?>" placeholder="이메일"
								onblur="checkEmail(this)">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">전화번호:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="TEL" value="<?=set_value('TEL')?>" placeholder="전화번호"
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
										<option value='<?=$i?>' <?=set_select('BIRTH_YEAR', $i)?>><?=$i?></option>
									<?php
									}
									?>
								</select>
								년
								<select class="form-control" name="BIRTH_MONTH">
									<?php
									for ($i = 1; $i <= 12; $i++) {
									?>
										<option value='<?=$i?>' <?=set_select('BIRTH_MONTH', $i)?>><?=$i?></option>
									<?php
									}
									?>
									
								</select>
								월
								<select class="form-control" name="BIRTH_DAY">
									<?php
									for ($i = 1; $i <= 31; $i++) {
									?>
										<option value='<?=$i?>' <?=set_select('BIRTH_DAY', $i)?>><?=$i?></option>
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
								<input type="text" class="form-control" name="ZIPCODE" value="<?=set_value('ZIPCODE')?>" placeholder="우편번호">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="row">
								<div class="col-sm-8">
									<input type="text" class="form-control" name="ADDRESS1" value="<?=set_value('ADDRESS1')?>" placeholder="주소">
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="ADDRESS2" value="<?=set_value('ADDRESS2')?>" placeholder="상세주소">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="EMAIL_CONFIRM[]" value="1">
									이메일 수신 동의
								</label>
							</div>
						</div>
					</div>
				
				</div>
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">회원가입</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>



function checkSubmit() {
	var frm = $("#formJoin")[0];
	
	if (! checkMemberForm(frm))
		return false;
	
	return true;
}


$("#formJoin").submit(function(event) {
	event.preventDefault();

	if (! checkSubmit()) {
		return;
	}
	
	$(this).get(0).submit();
	
})


</script>

