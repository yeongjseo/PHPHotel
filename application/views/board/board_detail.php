<?php
$user = $this->session->userdata('user');
$readonly = 'readonly';
if ($user) {
	if ($user->ID == 1)
		$readonly = '';
	if ($user->ID == $board->USER_ID)
		$readonly = '';
}
?>



<div class="container">
 	<div class="col-sm-10 col-sm-offset-1">
 		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="text-center">
					<h4><?=$type_long_desc?></h4>
				</div>
			</div>
				
			<div class="panel-body">
			
				<form class="form-horizontal" method="post" action="<?=CONTEXT.'/board/detail'?>" name="form1" id="form1" enctype="multipart/form-data">
					<input type="hidden" name="id" id="id" value="<?=$board->ID?>">
					<input type="hidden" name="type" id="type" value="<?=$type?>">
					<input type="hidden" name="page_num" id="page_num" value="<?=$paging->page_num?>">
					<input type="hidden" name="search_key" id="search_key" value="<?=$paging->search_key?>">
					<input type="hidden" name="search_val" id="search_val" value="<?=$paging->search_val?>">
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="account">작성자:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="account" name="account" value="<?=$board->ACCOUNT?>" placeholder="계정명" readonly>
						</div>
						
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="title">작성일:</label>
						<label class="control-label col-sm-4 pull-left">
							<span class="label label-primary lable-lg pull-left">
								<?=$board->WRITE_TIME?>
							</span>
						</label>
						<label class="control-label col-sm-2" for="title">조회수:</label>
						<label class="control-label col-sm-4">
							<span class="badge bg-red pull-left"><?=$board->READ_COUNT?></span>
						</label>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="title">제목:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="title" name="title" value="<?=$board->TITLE?>" <?=$readonly?> placeholder="제목">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="content">내용:</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="content" name="content" placeholder="내용" <?=$readonly?> rows="10"><?=$board->CONTENT?></textarea>
						</div>
					</div>
					<br>
				
 
					<div class="form-group" id="fileDiv">
						<label class="control-label col-sm-2" for="files">첨부파일:</label>
						
						<?php
						foreach ($files as $i=>$file) {
						?>
							<div>
								<input type="hidden" id="file_id_<?=$file->ID?>" name="file_id_<?=$file->ID?>" value="<?=$file->ID?>">
								<?php 
								if ($i == 0) {
								?>
									<div class="col-sm-5">
										<button type="button" class="btn btn-block btn-default" id="file_<?=$i?>" name="file_<?=$i?>" 
											data-id="<?=$file->ID?>" data-type="<?=$type?>">
											<?=$file->FILENAME?> (<?=$file->FILESIZE?> 바이트)
										</button>
									</div>
								<?php
								}
								else {
								?>
									<div class="col-sm-offset-2 col-sm-5">
										<button type="button" class="btn btn-block btn-default" id="file_<?=$i?>" name="file_<?=$i?>" 
											data-id="<?=$file->ID?>" data-type="<?=$type?>">
											<?=$file->FILENAME?> (<?=$file->FILESIZE?> 바이트)
										</button>
									</div>
								<?php
								}
								?>
								<div class="col-sm-5">
									<?php
									if ($readonly == '') {
									?>
										<button type="button" class="btn btn-danger" id="delete_<?=$i?>" name="delete_<?=$i?>">삭제</button>
									<?php
									}
									?>
								</div>
							</div>
						<?php
						}
						?>

					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<?php
							if ($readonly == '') {
							?>
								<button type="button" class="btn btn-success" id="addFile">파일추가</button>
								<button type="submit" class="btn btn-warning">수정</button>
								<button type="button" class="btn btn-danger" id="delete">삭제</button>			
							<?php
							}
							?>
							<button type="button"  class="btn btn-info pull-right" id="back">뒤로</button>
						</div>
					</div>
					
				</form>
				
				<div class="col-sm-offset-2 col-sm-10">
					<?php require_once VIEWPATH.'/board/board_reply_list.php' ?>
				</div>
			</div>
		</div>		
	</div>
</div>


<script>
var form1 = $("#form1");
//var fileCnt = '${fn:length(files) + 1}';
var fileCnt = '<?=count($files)?>';

form1.submit(function(e) {
	event.preventDefault();
	if ($("#title").val() == "") {
		openModalInfo("알림", "제목을 입력하세요.")
		return;
	}
	
	if ($("#content").val() == "") {
		openModalInfo("알림", "내용을 입력하세요.")
		return;
	}
	
	$(this).get(0).submit();
});

$('#back').on("click", function(ev) {
	self.location = "<?=CONTEXT.'/board/list'?>" +
					"?type=" + "<?=$type?>" +
					"&" + "<?=$paging->make_query($paging->page_num)?>";
	
});


$("#delete").on("click", function(event) {
	var frm = new commonForm(); 
	frm.setUrl("<?=CONTEXT.'/board/delete'?>");
	frm.addParam("type", form1.find("#type").val());
	frm.addParam("id", form1.find("#id").val());
	frm.addParam("page_num", form1.find("#page_num").val());
	frm.addParam("search_key", form1.find("#search_key").val());
	frm.addParam("search_val", form1.find("#search_val").val());
	frm.submit();
	
})

$("button[name^='file']").on("click", function(e) {
	e.preventDefault();
	
	var obj = $(this);
	var idx = obj.attr("data-id");
	var type = obj.attr("data-type");

	var frm = new commonForm();
    frm.setUrl("<?=CONTEXT.'/board/file_download'?>");
    frm.addParam("type", type);
    frm.addParam("file_id", idx);
    frm.submit();
	
});


$("button[name^='delete']").on("click", function(e){
	e.preventDefault();
	var isFirst = false;
	if ($("#fileDiv button[name^=delete]").first().is($(this)))
		isFirst = true;		
	
	$(this).parent().parent().remove();
	
	if (isFirst && $("#fileDiv div > div:nth-child(2)"))
		$("#fileDiv div > div:nth-child(2)").removeClass("col-sm-offset-2")
	
});


$('#addFile').on("click", function(e) {
	var offset = '';
	if ($("#fileDiv div").length != 0)
		offset = 'col-sm-offset-2 ';
	var str = 
		"<div>" + 
			"<div class='" + offset + " col-sm-5'>" + 
				"<input type='file' class='form-control' id='file_" + fileCnt + "' name='file_" + fileCnt + "'/>" + 
			"</div>" + 
			"<div class='col-sm-5'>" + 
				"<button type='button' class='btn btn-danger' id='delete_" + fileCnt + "' name='delete_" + fileCnt + "'>삭제</button>" +
			"</div>" + 
		"</div>";
		
	$("#fileDiv").append(str);
	
	$("button[name='delete_" + (fileCnt++) + "']").on("click", function(e) {
		e.preventDefault();
		
		var isFirst = false;
		if ($("#fileDiv button[name^=delete]").first().is($(this)))
			isFirst = true;		
		
		$(this).parent().parent().remove();
		
		if (isFirst && $("#fileDiv div > div:nth-child(1)"))
			$("#fileDiv div > div:nth-child(1)").removeClass("col-sm-offset-2")
		

	});

});


</script>







