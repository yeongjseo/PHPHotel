<style>
textarea { resize:none; height:200px;}
</style>

<div class="container">

	<form class="form-horizontal" method="post" action="boardDetail.do" id="form1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="text-center">
					에러 디버그 페이지
				</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-sm-2">에러발생위치:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="errorLoc" value="${errorLoc}" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">에러이유:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="errorReason" value="${errorReason}" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">리퀘스트 파라미터:</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="errorParam" rows="10" readonly>${errorParam}</textarea>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="button" class="btn btn-danger" id="send">에러정보 전송</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>