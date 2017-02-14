
<div class="container">

	<div class="container-fluid text-center bg-grey">
		
		<div class="row text-center slideanim">
			<?php
			for ($i = 0; $i < count($room_types) && $i < 3; $i++) {
				$room_type = $room_types[$i];
			?>
				<div class="col-sm-4">
					<div class="thumbnail" data-toggle="modal" data-target="#lightbox">
						<?php
						foreach ($room_type->files as $file) {
						?>
							<img class="img-responsive img-rounded" 
								src="<?=CONTEXT."/static/img/room/{$file->FILENAME}"?>" alt="Image">
						<?php
							break;
						}
						?>
						<p><strong><?=$room_type->TYPE_DESC?></strong></p>
						<p>최대 <?=$room_type->MAXPAX?>명</p>
						<p><?=$room_type->COST?>원</p>
					</div>
				</div>
			<?php
			}
			?>
		</div>
			
		<div class="row text-center slideanim">		
			<?php
			for ($i = 3; $i < count($room_types) && $i < 6; $i++) {
				$room_type = $room_types[$i];
			?>
				<div class="col-sm-4" data-toggle="modal" data-target="#lightbox">
					<div class="thumbnail">
						<?php
						foreach ($room_type->files as $file) {
						?>
							<img class="img-responsive img-rounded" 
								src="<?=CONTEXT."/static/img/room/{$file->FILENAME}"?>" alt="Image">
						<?php
							break;
						}
						?>
						<p><strong><?=$room_type->TYPE_DESC?></strong></p>
						<p>최대 <?=$room_type->MAXPAX?>명</p>
						<p><?=$room_type->COST?>원</p>
					</div>
				</div>
			<?php
			}
			?>
		</div>

		<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
				<div class="modal-content">
					<div class="modal-body"><img src="" alt="" /></div>
				</div>
			</div>
		</div>

		<br>
	</div>
	
</div>

<script>
var $lightbox = $('#lightbox');

$('[data-target="#lightbox"]').on('click', function(event) {
	var $img = $(this).find('img'), 
	src = $img.attr('src'),
	alt = $img.attr('alt'),
	css = {
		'maxWidth': $(window).width() - 100,
		'maxHeight': $(window).height() - 100
	};

	$lightbox.find('.close').addClass('hidden');
	$lightbox.find('img').attr('src', src);
	$lightbox.find('img').attr('alt', alt);
	$lightbox.find('img').css(css);
});

$lightbox.on('shown.bs.modal', function (e) {
	var $img = $lightbox.find('img');
        
	$lightbox.find('.modal-dialog').css({'width': $img.width()});
	$lightbox.find('.close').removeClass('hidden');
});


</script>
	