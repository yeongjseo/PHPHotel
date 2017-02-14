<div class="container">
	<div class="panel panel-primary">
  		<div class="panel-heading">
  			<div class="text-center">
  				<h4>룸 예약 관리</h4>
  			</div>
  		</div>
  		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="btn-group pull-right">
		  				<button type="button" class="btn btn-default" id="yearBefore">
		  					<span class="glyphicon glyphicon-chevron-left"></span>
		  				</button>
						<button type="button" class="btn btn-primary disabled" id="yearCur" 
							data-year="<?=$search->year_cur?>">
							<?=$search->year_cur?>년
						</button>
						<button type="button" class="btn btn-default" id="yearNext">
							<span class="glyphicon glyphicon-chevron-right"></span>
						</button>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="btn-group">
						<button type="button" class="btn btn-default" id="monthBefore">
							<span class="glyphicon glyphicon-chevron-left"></span>
						</button>
						<button type="button" class="btn btn-primary disabled" id="monthCur" 
							data-month="<?=$search->month_cur?>">
							<?=$search->month_cur?>월
						</button>
						<button type="button" class="btn btn-default" id="monthNext">
							<span class="glyphicon glyphicon-chevron-right"></span>
						</button>
					</div>
				</div>
			</div>
			<br>

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>룸</th>
						<?php
						for ($day = 1; $day <= $search->day_last; $day++) {
						?>
							<th><?=$day?></th>
						<?php 
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($rooms as $i=>$room) {
					?>
						<tr data-roomId="<?=$room->ID?>">
							<td> 
								<?="{$room->TYPE_DESC} {$room->ROOM_NUM}" ?>
							</td>
		
							<?php
							for ($day = 1; $day <= $search->day_last; $day++) {
							?>
								<td class="<?="item-{$room->ID}-{$day}"?>" data-id="<?=$room->ID?>">
									<?=$day?>
								</td>
							<?php
							}
							?>
						</tr>
					<?php
					}
					?>
				</tbody>		
			</table>
		</div>
	</div>

</div>



<script>

function getRandomColor() {
	var letters = '0123456789ABCDEF';
	var color = '#';
	for (var i = 0; i < 6; i++ ) {
		color += letters[Math.floor(Math.random() * 16)];
	}
	return color;
}

<?php 
foreach ($rooms as $r=>$room) {
	foreach ($room->reserves as $reserve) {
		$mon_start = (new DateTime($reserve->DATE_START))->format('n');
		$mon_end = (new DateTime($reserve->DATE_END))->format('n');
		$day_start = (new DateTime($reserve->DATE_START))->format('j');
		$day_end = (new DateTime($reserve->DATE_END))->format('j');
		
		if ($mon_start < $search->month_cur)
			$day_start = 1;
		if ($search->month_cur < $mon_end)
			$day_end = $search->day_last;
?>
		var color = getRandomColor();
		<?php
		$i = 0;
		for ($day = $day_start; $day <= $day_end; $day++) {
		?>

			var item = $("<?=".item-{$room->ID}-{$day}"?>");

			item.attr("data-id", "<?="{$reserve->ID}"?>");
		
			<?php 
			if ($day_start == $day) {
			?>
				item.css("background", "linear-gradient(to right, transparent 50%, " + color + " 51%)");
			<?php 
			}
			if ($day_start < $day && $day < $day_end) {
			?>
				item.css("background-color", color);
			<?php 
			}
			if ($day_end == $day) {
			?>
				item.css("background", "linear-gradient(to right, " + color + " 50%, " + " transparent 51%)");
			<?php				
			}
			
			$i++;
		}
	}
}
?>


$("td[class^='item-']").on("click", function(e) {
	e.preventDefault();
	
	var obj = $(this);
	var id = obj.attr("data-id");
	console.log(id);

	var frm = new commonForm();

	<?php 
	$return_url = rawurlencode($_SERVER['REQUEST_URI']);
	?>
	
    frm.setUrl("<?=CONTEXT."/admin/reserve_detail?return_url={$return_url}"?>");
    frm.addParam("action", "get");
    frm.addParam("reserve_id", id);
    frm.submit();
	
});


$("#yearBefore").on("click", function(e) {
	var yearCur = $("#yearCur").attr("data-year");
	var monthCur = $("#monthCur").attr("data-month");	
	var frm = new commonForm();
	yearCur--;
	frm.addParam("year_cur", yearCur);
	frm.addParam("month_cur", monthCur);
	frm.send();
});

$("#yearNext").on("click", function(e) {
	var yearCur = $("#yearCur").attr("data-year");
	var monthCur = $("#monthCur").attr("data-month");	
	var frm = new commonForm();
	yearCur++;
	frm.addParam("year_cur", yearCur);
	frm.addParam("month_cur", monthCur);
	frm.send();
});

$("#monthBefore").on("click", function(e) {
	var yearCur = $("#yearCur").attr("data-year");
	var monthCur = $("#monthCur").attr("data-month");
	monthCur--;
	if (monthCur == 0) {
		yearCur--;
		monthCur = 12;
	}
	
	var frm = new commonForm();
	frm.addParam("year_cur", yearCur);
	frm.addParam("month_cur", monthCur);
	frm.send();
});

$("#monthNext").on("click", function(e) {
	var yearCur = $("#yearCur").attr("data-year");
	var monthCur = $("#monthCur").attr("data-month");	
	monthCur++;
	if (monthCur > 12) {
		yearCur++;
		monthCur = 1;
	}
	
	var frm = new commonForm();
	frm.addParam("year_cur", yearCur);
	frm.addParam("month_cur", monthCur);
	frm.send();
});












</script>

