<div class="container">

	<div class="panel panel-primary">
  		<div class="panel-heading">
  			<div class="text-center">
  				<h4>룸 리스트 관리</h4>
  			</div>
  		</div>
  		
  		<div class="panel-body">
		   	<div class="row">
		   		<div class="col-sm-6">
	   				<button type="button" class="btn btn-primary" id="write">룸 만들기</button>
		   		</div>
		   	
				<div class="col-sm-6" >
					<!--
					<form class="form-inline pull-right">
				   		<select name="searchKey" class="form-control">
				  			<option value="" selected="selected">전체검색</option>
				 	   	  	<option value="TYPE_DESC" <?php if ($paging->search_key == 'TYPE_DESC' ) echo "selected='selected'"?>>룸타입검색</option>
				 	   	  	<option value="TITLE" <?php if ($paging->search_key == 'TITLE' ) echo "selected='selected'"?>>제목검색 </option>
				 	   	  	<option value="CONTENT" <?php if ($paging->search_key == 'CONTENT' ) echo "selected='selected'"?>>내용검색 </option>
				   		</select>
				   		
			   			<input type="text"  class="form-control" id="searchVal" name="search_val" value="<?=$paging->search_val?>">
	
			   			<input type="button" class="form-control btn btn-warning" id="search" value="검색">
	
			 	  	</form>
			 	  	 -->
				</div>
			</div>
	 
		
			<table class="table table-striped table-hover table-responsive">
				<tr>
					<th style="width:30px">번호</th>
					<th>ID</th>
					<th>호수</th>
					<th>룸타입</th>
					<th>최대인원</th>
					<th>가격</th>
				</tr>
			
				<?php
				foreach ($rooms as $room) {
				?>
					<tr>
						<td><?=$room->RN?></td>
						<td><a href='#'><?=$room->ID?></a></td>
						<td><?=$room->ROOM_NUM?></td>
						<td><?=$room->TYPE_DESC?></td>
						<td><?=$room->MAXPAX?>명</td>
						<td><?=$room->COST?>원</td>
					</tr>
				<?php
				}
				?>
			</table>
		
		
			<div class="panel-footer">
		
				<div class="text-center">
					<ul class="pagination">
						<?php
						if ($paging->page_start > $paging->page_max) {
						?>
							<li><a href="<?=CONTEXT."/admin/room_list?{$paging->make_query($paging->page_start - $paging->page_max)}"?>">&laquo;</a></li>
							
						<?php
						}
						
						for ($i = $paging->page_start; $i <= $paging->page_end; $i++) {
							$active = ''; 
							if ($paging->page_num == $i)
								$active = "class='active'";
						?>				
							<li <?=$active?>><a href="<?=CONTEXT."/admin/room_list?{$paging->make_query($i)}"?>" class="page"><?=$i?></a></li>
						<?php
						}
						
						if ($paging->page_end < $paging->page_count) {
						?>
							<li><a href="<?=CONTEXT."/admin/room_list?{$paging->make_query($paging->page_start + $paging->page_max)}"?>">&raquo;</a></li>				
						<?php
						}
						?>
						
					</ul>
				</div>
			</div>
		</div>
	</div>

</div>

<script>

$('#search').on("click", 
	function(event) {
		self.location = "<?=CONTEXT.'/admin/admin_list'?>" + 
						"?pageNum=1" +		
						"&searchKey=" + $("select option:selected").val() + 
						"&searchVal=" + $('#searchVal').val();
	});

</script>


