
<div class="container">

	<div class="col-md-8 col-md-offset-2">
	
		<h2 class="text-center">예약 정보</h2>
	
		<div class="panel panel-primary">
		
			<div class="panel-heading">예약 정보</div>
	
			<div class="panel-body">
				<div class="row">	
					<div class="col-sm-3">
						<label for="account">예약번호:</label>
						<input type="text" class="form-control" id="reserveId" value="<?=$room->ID?>" readonly>
					</div>
					<div class="col-sm-3">
						<label for="account">예약자:</label>
						<input type="text" class="form-control" value="<?=$room->ACCOUNT?>" readonly>
					</div>
					<div class="col-sm-3">
						<label for="roomType">룸타입:</label>
						<input type="text" class="form-control" value="<?=$room->TYPE_DESC?>" readonly>
					</div>
					<div class="col-sm-3">
						<label for="roomNum">룸번호:</label>
						<input type="text" class="form-control" value="<?=$room->ROOM_NUM?>" readonly>
					</div>
				</div>
				
				
				<div class="row">
					<div class="col-sm-3">
						<label for="checkin">체크인:</label>
						<input type="text" class="form-control" value="<?=$room->DATE_START?>" readonly> 
					</div>
			
					<div class="col-sm-3">
						<label for="checkout">체크아웃:</label>
						<input type="text" class="form-control" value="<?=$room->DATE_END?>" readonly>
					</div>
			
					<div class="col-sm-3">
						<label for="dateReserve">예약일:</label>
						<input type="text" class="form-control" value="<?=$room->DATE_RESERVE?>" readonly> 
					</div>
					
					<div class="col-sm-3">
						<label for="pax">숙박인원</label>
						<input type="text" class="form-control" value="<?=$room->PAX?>" readonly>
					</div>
				</div>
					
				<div class="row">
					<div class="col-sm-3 pull-right">
						<label for="blank"></label>
						<?php
						$user = $this->session->userdata('user');
						if ($user && $user->ID == $room->USER_ID) {
						?>
							<button type="button" class="form-control btn btn-danger" id="delete">삭제</button>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script>

$('#delete').on("click", function(event) {
	
	var jsonObj = {
		id : $("#reserveId").val()
	}
	
	var jsonWrap = {json_body: JSON.stringify(jsonObj)};
	$.post("<?=CONTEXT.'/reserve/delete'?>", jsonWrap, function(jsonDataRes) {
		
		if (jsonDataRes.result == "success") {
			openModalInfo("알림", "예약을 삭제하였습니다.");

			self.location = "<?=$return_url?>";
		}
		else {
			openModalInfo("예약 삭제 실패", jsonDataRes.reason);
		}
	})



}); 

</script>
