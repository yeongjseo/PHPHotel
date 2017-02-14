<div class="box box-primary">
	<div class="box-header">
		<div class="text-center">
			<h4>댓글작성</h4>
		</div>
	</div>
	
	<?php
	$user = $this->session->userdata('user');
	$readonly = 'readonly';
	if ($user) {
	?>
		<div class="box-body">
			<form class="form-horizontal" method="post">
				<div class="form-group">
					<label class="control-label col-sm-2" for="account">작성자:</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="replyWriter" name="replyWriter" 
							placeholder="작성자" value="<?=$user->ACCOUNT?>" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="account">내용:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="replyContent" name="replyContent" 
							placeholder="내용">
					</div>
				</div>
			</form>
		</div>
		<div class="box-footer">
			<div class="col-sm-2 col-sm-offset-10">
				<button type="submit" class="btn btn-primary" id="addReply">댓글 달기</button>
			</div>
		</div>
	<?php
	} 
	else {
	?>
		<div class="box-body">
			<div class="col-sm-offset-10 col-sm-2 ">
				<button type="button" class="btn btn-primary" id="loginReply">로그인</button>
			</div>
		</div>
	<?php
	} 
	?>
</div>

<ul class="timeline" id="replyList">
    <li class="time-label">
        <span class="bg-red">
            댓글리스트 <small id='replyCount'> [${replyCount}] </small>
        </span>
    </li>
</ul>

<button type="button" class="btn btn-primary" id="nextReply">다음 댓글</button>


<div id="modalModifyReply" class="modal modal-primary fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" data-rno>
				<p>
					<input type="text" id="replyContent" class="form-control">
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" id="updateReply">수정</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
			</div>
		</div>
	</div>
</div>

<script id="templateReplyItem" type="text/x-handlebars-template">
	{{#each .}}
		<li class="replyItem" data-replyId={{ID}}>
			<i class="fa fa-comments bg-blue"></i>
			<div class="timeline-item" >
				<span class="time">
					<i class="fa fa-clock-o">{{WRITE_TIME}}</i>
				</span>
                <h2 class="timeline-header"><strong>{{RN}}</strong>-{{ACCOUNT}}</h2>
                <div class="timeline-body">{{CONTENT}}</div>
				<div class="timeline-footer">
					{{#checkWriter ACCOUNT}}
                		<button class="btn btn-warning btn-xs" data-toggle="modal" id="modifyReply">수정</button>
						<button class="btn btn-danger btn-xs" data-toggle="modal" id="deleteReply">삭제</button>
					{{/checkWriter}}
				</div>
	        </div>
    	</li>
	{{/each}}
</script>



<script>
var pageNum = 1;

Handlebars.registerHelper("checkWriter", function(account, block) {
	var accum = '';
	<?php  
	$user = $this->session->userdata('user');
	$account = '';
	if ($user && $user->ACCOUNT)
		$account = $user->ACCOUNT;
	?>
	if (account == '<?=$account?>')
		accum += block.fn();

	return accum;
});

var DATA;

function getReplyPage(page) {
	pageNum = page;

	var url = "<?=CONTEXT."/board/reply_list?board_id={$board->ID}&page_num="?>" + pageNum;
	$.getJSON(url, function (data) {
		DATA = data;
		var template = Handlebars.compile($('#templateReplyItem').html());
		var html = template(data.list);
		
		$("#replyList li:last").after(html);
		
		$("#replyCount").html("[ " + data.replyCount + "개 ]");
		
		console.log("replycount: " + data.replyCount);
		console.log("replyList: " + $("#replyList li").length);
		if (data.replyCount > ($("#replyList li").length - 1))
			$("#nextReply").show();
		else
			$("#nextReply").hide();
	})
	
	
}

$("#addReply").on("click", function() {
	if ($("#replyWriter").val() == "") {
		openMessageInfo("알림", "작성자 이름을 입력하세요.");
		return;
	}

	if ($("#replyContent").val() == "") {
		openMessageInfo("알림", "댓글 내용을 입력하세요.");
		return;
	}

	/*
	// 방법 1 
	// Form Data
	// 		data:{"board_id":"203","account":"admin","content":"dd"}
	$.ajax({
		type : "post",
		url : "<?=CONTEXT."/board/reply_insert"?>", 
		data : {data: JSON.stringify({
			board_id : '<?=$board->ID?>',
			account : $("#replyWriter").val(),
			content : $("#replyContent").val()
		})},
		success : function(data, status) {
			if (data.result == "success") {
				$(".replyItem").remove();
				getReplyPage("1");
			} else {
				openModalError("에러", "댓글 달기를 실패했습니다.");
			}
		}
	});
	*/

			
	// 방법 2 
	// Content-Type:application/json
	// Request Payload
	// 		{"board_id":"203","account":"admin","content":"ddd"}
	$.ajax({
		type : "post",
		url : "<?=CONTEXT."/board/reply_insert"?>", 
		headers : {
			"Content-Type" : "application/json",
			"X-HTTP-Method-Override" : "POST"
		},
		data : JSON.stringify({
			board_id : '<?=$board->ID?>',
			account : $("#replyWriter").val(),
			content : $("#replyContent").val()
		}),
		success : function(data, status) {
			if (data.result == "success") {
				$(".replyItem").remove();
				getReplyPage("1");
			} else {
				openModalError("에러", "댓글 달기를 실패했습니다.");
			}
		}
	});

				
	$("#replyContent").val("");
	
	
});

$("#nextReply").on("click", function() {
	getReplyPage(++pageNum);
	
});

$("#loginReply").on("click", function() {
	location.href = "<?=CONTEXT."/member/login"?>";
	
});

$("#replyList").on("click", "#modifyReply", function() {
	
	replyId = $(this).closest(".replyItem").attr("data-replyId");
	content = $(this).parents(".replyItem").find(".timeline-body").text()
	console.log(replyId + ", " + content);
	
	$("#modalModifyReply .modal-title").html(replyId);
	$("#modalModifyReply #replyContent").val(content);
	
	$("#modalModifyReply").modal();
	
});

$("#updateReply").on("click", function() {
	
	id = $("#modalModifyReply .modal-title").html();
	content = $("#modalModifyReply #replyContent").val();
	
	$("#modalModifyReply").modal('hide');
	$("#modalModifyReply .modal-title").html("");
	$("#modalModifyReply #replyContent").val("");
	
	$.ajax({
		type : "post",
		url : "<?=CONTEXT."/board/reply_update"?>",
		headers : {
			"Content-Type" : "application/json",
			"X-HTTP-Method-Override" : "POST"
		},
		data : JSON.stringify({
			id : id,
			content : content,
		}),
		success : function(data, status) {
			if (data.result == "success") {
				$(".replyItem").remove();
				getReplyPage("1");
			} else {
				openModalError("에러", data.reason);
			}
		}
	});
});


$("#replyList").on("click", "#deleteReply", function() {
	replyId = $(this).closest(".replyItem").attr("data-replyId");
	
	$.ajax({
		type : "post",
		url : "<?=CONTEXT."/board/reply_delete"?>",
		headers : {
			"Content-Type" : "application/json",
			"X-HTTP-Method-Override" : "POST"
		},
		data : JSON.stringify({
			id : replyId,
		}),
		success : function(data, status) {
			if (data.result == "success") {
				$(".replyItem").remove();
				getReplyPage("1");
			} else {
				openModalError("에러", data.reason);
			}
		}
	});
});



$(function() {
	getReplyPage(pageNum);
});
	
	


</script>