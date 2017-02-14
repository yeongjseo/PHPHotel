
<div class="container">
	<div class="col-md-8 col-md-offset-2">

		<div class="panel panel-danger">
			<div class="panel-heading">
				<div class="text-center">
					<h4>예약정보</h4>
				</div>
			</div>
			<div class="panel-body">
				<?php
				if (count($reserves) == 0) {
				?>
					<div class="text-center">
						<h5>예약된 정보가 없습니다.</h5>
					</div>		
				<?php
				}
				?>
				
				<?php
				foreach ($reserves as $i=>$reserve) {
				?>
					<div class="panel panel-primary" id="reserveDTO">
						<div class="panel-heading">예약</div>
				
						<div class="panel-body">
							<div class="row">	
								<div class="col-xs-3">
									<label for="account">예약번호:</label>
									<input type="text" class="form-control" id="reserveId" value="<?=$reserve->ID?>" readonly>
								</div>
								<div class="col-xs-3">
									<label for="account">예약자:</label>
									<input type="text" class="form-control" value="<?=$reserve->ACCOUNT?>" readonly>
								</div>
								<div class="col-xs-3">
									<label for="roomType">룸타입:</label>
									<input type="text" class="form-control" value="<?=$reserve->ROOM_TYPE_DESC?>" readonly>
								</div>
								<div class="col-xs-3">
									<label for="roomNum">룸번호:</label>
									<input type="text" class="form-control" value="<?=$reserve->ROOM_NUM?>" readonly>
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-sm-3">
									<label for="checkin">체크인:</label>
									<input type="text" class="form-control" value="<?=$reserve->DATE_START?>" readonly> 
								</div>
						
								<div class="col-sm-3">
									<label for="checkout">체크아웃:</label>
									<input type="text" class="form-control" value="<?=$reserve->DATE_END?>" readonly>
								</div>
						
								<div class="col-sm-6">
									<label for="dateReserve">예약일:</label>
									<input type="text" class="form-control" value="<?=$reserve->DATE_RESERVE?>" readonly> 
								</div>
								
							</div>
		
						</div>
						
						<div class="panel-footer">					
							<div class="row">
								<div class="col-sm-3 pull-right">
									<label for="blank"></label>
									<button type="button" class="form-control btn btn-danger" 
											id="<?="reserveDelete_{$i}"?>">삭제</button>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>

<script>

$("button[id^='reserveDelete']").on("click", function(event) {
	var that = $(this);
	// var rid = $("#reserveId").val();
	var rid = that.closest('#reserveDTO').find('#reserveId').val();
	var jsonObj = {
		id : rid
	}
	var jsonWrap = {json_body: JSON.stringify(jsonObj)};
	$.post("<?=CONTEXT.'/reserve/delete'?>", jsonWrap, function(jsonDataRes) {
		if (jsonDataRes.result == "success") {
			openModalInfo("알림", "예약 (에약번호 " + rid + ")을 삭제하였습니다.");
			
			// self.location = "reserveList.do";
			that.closest('#reserveDTO').remove();
		}
		else {
			openModalInfo("예약 삭제 실패", jsonDataRes.reason);
		}
	})



}); 

</script>
