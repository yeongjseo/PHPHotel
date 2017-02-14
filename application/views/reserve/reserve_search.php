<div class="container">
	<div class="col-sm-10 col-sm-offset-1">
		<form id="formReserveSearch" method="get" action="<?=CONTEXT.'/reserve/search'?>">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="form-group col-sm-3">
							<label for="dateStart">체크인:</label>
							<input type="text" class="form-control" id="dateStart" name="date_start" 
									value="<?=$search->date_start?>" placeholder="체크인 날짜" />
						</div>
						<div class="form-group col-sm-3">
							<label for="dateEnd">체크아웃:</label>
							<input type="text" class="form-control" id="dateEnd" name="date_end" 
									value="<?=$search->date_end?>" placeholder="체크아웃"/>
						</div>
						<div class="form-group col-sm-2">
							<label for="dateCount">숙박일수:</label>
							<input type="text" class="form-control" id="dateCount" name="date_count" 
									value="<?=$search->date_count?>" readonly="readonly">
						</div>
					
						<div class="form-group col-sm-2">
							<label for="guestNum">투숙객수</label>
							<select class="form-control" id="guestNum" name="guest_num">
								<?php
								$nums = array (1, 2, 3, 4, 5, 10);
								foreach ($nums as $num) {
									if ($num == $search->guest_num)
										$select = ' selected="selected"';
									else
										$select = '';
								?>
									<option value='<?=$num?>' <?=$select?>>
										<?=$num?>
									</option>
								<?php
								}
								?>
							</select>
						</div>
						
						<div class="form-group col-sm-2">
							<label for="reserve"></label>
							<button type="submit" class="form-control btn btn-warning" id="reserveSearch" >검색</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>


	<?php
	if (! is_array($vacant_room_types)) {
		require_once VIEWPATH.'room/room_type_card.php';
	}
	?>

	<div class="col-sm-10 col-sm-offset-1">
		<table class="table table-hover table-striped table-responsive reserveDTO" style="display:none">
			<thead>
				<tr>
					<th>예약번호</th>
					<th>방타입</th>
					<th>체크인</th>
					<th>체크아웃</th>
					<th></th>
				</tr>
			</thead>
		</table>
	</div>


	<form id="formRoomList" method="post">
		<input type="hidden" id="dateStart" name="dateStart" value="${dateStart}">
		<input type="hidden" id="dateEnd" name="dateEnd" value="${dateEnd}">
		<input type="hidden" id="dateCount" name="dateCount" value="${dateCount}">
		<input type="hidden" id="guestNum" name="guestNum" value="${guestNum}">

		<?php
		if (is_array ($vacant_room_types)) {
			foreach ($vacant_room_types as $room_type) {
		?>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="row">
					<div class="col-sm-8">
						<div id="myCarousel" class="carousel slide" data-ride="carousel">
								<!-- Indicators -->
								<ol class="carousel-indicators">
									<?php
									foreach ($room_type->files as $i=>$file) {
										if ($i == 0)
											$class = 'class="active"'; 
										else
											$class = '';
									?>	
										<li data-target="#myCarousel" data-slide-to="<?=$i?>" <?=$class?>></li>
									<?php
									}
									?>
								</ol>

								<!-- Wrapper for slides -->
								<div class="carousel-inner" role="listbox">
									<?php
									foreach ($room_type->files as $i=>$file) {
										if ($i == 0)
											$class = 'class="item active"';
										else
											$class = 'class="item"';
											
									?>
										<div <?=$class?>>
											<img src="<?=CONTEXT."/static/img/room/{$file->FILENAME}"?>" alt="Image">
											<div class="carousel-caption">
												<h3><?=$room_type->TYPE_DESC?>룸</h3>
												<p><?=$room_type->MAXPAX?>명/<?=$room_type->COST?>원</p>
											</div>      
										</div>
									<?php
									}
									?>
								</div>
							
							<!-- Left and right controls -->
							<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="well">
							<p><?=$room_type->TYPE_DESC?></p>
						</div>
						<div class="well">
							<p>최대 <?=$room_type->MAXPAX?></p>
						</div>
						<div class="well">
							<p><?=$room_type->COST?>원</p>
						</div>
						<div>
							<button type="button" class="btn btn-success" id="reserveConfirm" data-roomType='<?=$room_type->TYPE?>'>예약</button>
						</div>
					</div>
				</div>
				
			</div>
			<div class="form-group col-xs-12"></div> <!-- FOR BLANK LINE -->
		<?php
			}
		}
		?>
	</form>

	
</div>

<script id="templateReservedItem" type="text/x-handlebars-template">
    <tr>
        <td>{{reserveId}}</td>
        <td>{{roomTypeDesc}}</td>
        <td>{{dateStart}}</td>
		<td>{{dateEnd}}</td>
		<td><button type="input" class="btn btn-warning" id="reserveDelete" date-roomType="{{roomType}}" data-reserveId="{{reserveId}}">
			삭제</button>
		</td>
    </tr>
</script>



<script>

$(document).ready(function() {
	var dateStart = $('#dateStart');
	var dateEnd = $('#dateEnd');
	var oneDay = 1000 * 60 * 60 * 24;
	var options = {
		format : 'yyyy/mm/dd',
		startDate:'+1d',
		container : "body",
		todayHighlight : true,
		language : 'ko', 
		autoclose : true,
	};
	dateStart.datepicker(options).on('changeDate', function(e) {
		var dStart = new Date(e.format(0, "yyyy/mm/dd"));
		var dEnd = new Date(dStart.getTime() + oneDay);
		dateEnd.datepicker('setStartDate', dEnd);
	});
	dateEnd.datepicker(options).on('changeDate', function(e) {
		var dStart = new Date($('#dateStart').data('datepicker').getFormattedDate('yyyy/mm/dd'));
		var dEnd = new Date(e.format(0, "yyyy/mm/dd"));
		  
		var diff = Math.ceil((dEnd.getTime() - dStart.getTime()) / oneDay); 
		$('#dateCount').val(diff);
	});
	
	<?php
	if (is_array($vacant_room_types) && ! count($vacant_room_types)) {
	?>
		openModalInfo("알림", "정해진 구간에 룸이 모두 예약되었습니다.")
	<?php
	}
	?>
	
})

var formReserveSearch = $("#formReserveSearch");
formReserveSearch.submit(function(e) {
	event.preventDefault();
	if ($("#dateStart").val() == "") {
		openModalInfo("알림", "체크인을 지정하세요.");
		return;
	}
	
	if ($("#dateEnd").val() == "") {
		openModalInfo("알림", "체크아웃을 지정하세요.");
		return;
	}
	
	var dStart = new Date($('#dateStart').data('datepicker').getFormattedDate('yyyy/mm/dd'));
	var dEnd = new Date($('#dateEnd').data('datepicker').getFormattedDate('yyyy/mm/dd'));
	if (dEnd.getTime() - dStart.getTime() < 0) {
		openModalInfo("알림", "체크아웃이 체크인보다 빠릅니다.");
		return;
	}
	
	$(this).get(0).submit();
});


function addReserveDTO(reserveDTO) {
	var template = Handlebars.compile($('#templateReservedItem').html());
	var html = template(reserveDTO);
	$(".reserveDTO tr:last").after(html);
	
	$(".reserveDTO").show();
}
	
$("#formRoomList").on("click", "#reserveConfirm", function(event) {
	var roomType = $(this).attr("data-roomType");
	var dateStart = $('#dateStart').val(); 
	var dateEnd = $('#dateEnd').val();
	var dateCount = $('#dateCount').val();
	var guestNum = $('#guestNum').val();

	<?php
	if (! ($user= $this->session->userdata('user'))) {
	?>
		openModalInfo("알림", "로그인 후 예약하세요");
	<?php
	}
	?>
	
	var jsonObj = {
		date_start : $('#dateStart').val(), 
		date_end : $('#dateEnd').val(),
		date_count : $('#dateCount').val(),
		guest_num : $('#guestNum').val(),
		room_type_id : roomType,
	}
	var jsonWrap = {json_body: JSON.stringify(jsonObj)};
	$.post("<?=CONTEXT.'/reserve/insert'?>", jsonWrap, function(jsonDataRes) {

		// parseJSON: XML -> JSON, mine is already JSON.
		// var obj = jQuery.parseJSON(jsonDataRes);
		if (jsonDataRes.result == "success") {
			openModalInfo("알림", "예약하였습니다.");
			
			var reserveDTO = {
				dateStart:dateStart,   
				dateEnd:dateEnd,  
				dateCount:dateCount,
				guestNum:guestNum, 
				roomType:roomType,
				roomTypeDesc:jsonDataRes.room_type_desc,
				reserveId:jsonDataRes.reserve_id
			};
			
			addReserveDTO(reserveDTO);
		}
		else {
			openModalInfo("예약 실패", jsonDataRes.reason);
		}
	})
})

$(".reserveDTO").on("click", "#reserveDelete", function(event) {
	var reserveId = $(this).attr("data-reserveId");
	var that = $(this);
	var jsonObj = {
		id : reserveId 
	}
	var jsonWrap = {json_body: JSON.stringify(jsonObj)};
	$.post("<?=CONTEXT.'/reserve/delete'?>", jsonWrap, function(jsonDataRes) {
		if (jsonDataRes.result == "success") {
			openModalInfo("알림", "예약을 삭제하였습니다.");

			console.log($(this));
			that.closest('tr').remove();		
			//$(this).parent().remove();
			
		}
		else {
			openModalInfo("예약 삭제 실패", jsonDataRes.reason);
		}
		
	})
})
	
	

</script>


