
<style>
.overlay {
	margin: 0 auto;
    overflow: hidden;
    position: fixed;
    top: 80px;
    float:right;
    z-index:999;
}
</style>


<div class="container">
	<div class="overlay">
		<form class="form-inline pull-right" id="formSearchQuick" method="get" 
						action="<?=CONTEXT.'/reserve/search'?>">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="form-group">
	  					<input type="text" class="form-control" id="dateStart" name="date_start" placeholder="체크인" />
	  					<input type="text" class="form-control" id="dateEnd" name="date_end" placeholder="체크아웃"/>
	  					<button type="submit" class="btn btn-warning">검색</button>
	  				</div>
  				</div>
			</div>
		</form>
	</div>
</div>

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
		// $('#dateCount').val(diff);
	});
	
})

var formSearchQuick = $("#formSearchQuick");
formSearchQuick.submit(function(e) {
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

</script>