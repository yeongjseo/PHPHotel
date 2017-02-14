<?php require_once VIEWPATH.'common/include.php' ?>

<style>
textarea { resize:none; height:200px;}
</style>

<div class="container">

	<form class="form-horizontal" method="post" action="boardDetail.do" id="form1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="text-center">
					에러페이지
				</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-sm-2">에러코드</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="errorParam" rows="10" readonly>
HTTP상태코드 : 404에러
요청한 페이지를 찾을 수 없습니다.
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>