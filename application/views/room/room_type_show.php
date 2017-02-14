<div class="container">
	
	<div class="col-md-10 col-md-offset-1">
	
		<?php
		foreach ($room_types as $i=>$room_type) {
		?>
			<div class="panel panel-primary">
	  			<div class="panel-heading">
	  				<div class="text-center">
	  					<h4><?=$room_type->TYPE_DESC?>룸</h4>
	  				</div>
	  			</div>
	  		
	  			<div class="panel-body">
					<div class="row">
						<div class="col-sm-8">
							<div id="<?="myCarousel_{$i}"?>" class="carousel slide" data-ride="carousel">
								
								<!-- Indicators -->
								<ol class="carousel-indicators">
									<?php
									foreach ($room_type->files as $j=>$file) {
										if ($j == 0)
											$class = 'class="active"';
										else
											$class = '';
									?>
										<li data-target="<?="#myCarousel_{$i}"?>" data-slide-to="<?=$i?>" <?=$class?>></li>
									
									<?php 
									}
									?>
								</ol>
					
								<!-- Wrapper for slides -->
								<div class="carousel-inner" role="listbox">
									<?php 
									foreach ($room_type->files as $j=>$file) {
										if ($j == 0)
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
								<a class="left carousel-control" href="<?="#myCarousel_{$i}"?>" role="button" data-slide="prev">
									<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="right carousel-control" href="<?="#myCarousel_{$i}"?>" role="button" data-slide="next">
									<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
									<span class="sr-only">Next</span>
								</a>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="well">
								<p>방유형: <?=$room_type->TYPE_DESC?></p>
							</div>
							<div class="well">
								<p>최대인원: <?=$room_type->MAXPAX?></p>
							</div>
							<div class="well">
								<p>가격: <?=$room_type->COST?>원</p>
							</div>
							
							
						</div>
					</div>
				
				
				</div>
			</div>
		<?php
		}
		?>
	</div>
</div>
