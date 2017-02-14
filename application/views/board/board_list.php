<div class="container">
	<div class="panel panel-primary">
  		<div class="panel-heading">
  			<div class="text-center">
  			</div>
  				<h4><?=$type_long_desc?></h4>
  		</div>
  		
  		<div class="panel-body">
		   	<div class="row">
		   		<div class="col-sm-6">
		   			<?php
		   			//if (($user= $this->session->userdata('user'))) {
					?>
		   				<button type="button" class="btn btn-primary" id="write">작성하기</button>
		   			<?php
					//}
					?>
		   		</div>
		   	
				<div class="col-sm-6" >
					<form class="form-inline pull-right">
				   		<select name="search_key" class="form-control">
				  			<option value="" selected="selected">전체검색</option>
				 	   	  	<option value="ACCOUNT" <?php if ($paging->search_key == 'ACCOUNT' ) echo "selected='selected'"?>>작성자검색 </option>
				 	   	  	<option value="TITLE" <?php if ($paging->search_key == 'TITLE' ) echo "selected='selected'"?>>제목검색 </option>
				 	   	  	<option value="CONTENT" <?php if ($paging->search_key == 'CONTENT' ) echo "selected='selected'"?>>내용검색 </option>
				   		</select>
				   	
			   			<input type="text"  class="form-control" id="searchVal" name="search_val" value="<?=$paging->search_val?>">
	
			   			<input type="button" class="form-control btn btn-warning" id="search" value="검색">
	
			 	  	</form>
				</div>
			</div>
	
		
		
			<table class="table table-striped table-hover table-responsive">
				<tr>
					<th>번호</th>
					<th>제목</th>
					<th>내용</th>
					<th>작성자</th>
					<th>등록일</th>
					<th>조회수</th>
				</tr>
			
				<?php
				foreach ($list as $row) {
				?>
					<tr>
						<td><?=$row->RN?></td>
						<td>
							<a href="<?=CONTEXT."/board/detail?type={$type}&id={$row->ID}&{$paging->make_query()}"?>">
								<?=$row->TITLE?>
							</a>
						</td>
						<td><?=$row->CONTENT?></td>
						<td><?=$row->ACCOUNT?></td>
						
						<td><?=$row->WRITE_TIME?></td>
						<td><span class="badge bg-red"><?=$row->READ_COUNT?></span></td>
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
							<li><a href="<?=CONTEXT."/board/list?{$paging->make_query($paging->page_start - $paging->page_max)}&type={$type}"?>">&laquo;</a></li>
							
						<?php
						}
						
						for ($i = $paging->page_start; $i <= $paging->page_end; $i++) {
							$active = ''; 
							if ($paging->page_num == $i)
								$active = "class='active'";
						?>				
							<li <?=$active?>><a href="<?=CONTEXT."/board/list?{$paging->make_query($i)}&type={$type}"?>" class="page"><?=$i?></a></li>
						<?php
						}
						
						if ($paging->page_end < $paging->page_count) {
						?>
							<li><a href="<?=CONTEXT."/board/list?{$paging->make_query($paging->page_start + $paging->page_max)}&type={$type}"?>">&raquo;</a></li>				
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

$('#search').on("click", function(ev) {
	self.location = "<?=CONTEXT.'/board/list'?>" +
					"?page_num=1" +
					"&type=" + "<?=$type?>" + 
					"&search_key=" + $("select option:selected").val() + 
					"&search_val=" + $('#searchVal').val();
});

$('#write').on("click", function(event) {
	self.location = "<?=CONTEXT.'/board/insert'?>" + 
					"?type=" + "<?=$type?>" +
					"&search_key=" + "<?=$paging->search_key?>" + 
					"&search_val=" + "<?=$paging->search_val?>";
});

</script>

