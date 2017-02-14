
<div class="container">

	<div class="panel panel-primary">
  		<div class="panel-heading">
  			<div class="text-center">
  				<h4>예약 리스트</h4>
  			</div>
  		</div>
  		
  		<div class="panel-body">

			<div class="row">
		   		<div class="col-sm-6">
		   			<?php
		   			$user= $this->session->userdata('user');
		   			if ($user && $user->ID == 1) {
		   			?>
		   				<button type="button" class="btn btn-primary" id="write">작성하기</button>
		   			<?php
		   			}
		   			?>
		   			</c:if>
		   		</div>
		   	
				<div class="col-sm-6" >
					<form class="form-inline pull-right">
				   		<select name="searchKey" class="form-control">
				  			<option value="" selected="selected">전체검색</option>
				 	   	  	<option value="ACCOUNT" <?php if ($paging->search_key == 'ACCOUNT' ) echo "selected='selected'"?>>계정명검색</option>
					 		<option value="TEL" <?php if ($paging->search_key == 'TEL' ) echo "selected='selected'"?>>연락처</option>
						 	<option value="ADDRESS1" <?php if ($paging->search_key == 'ADDRESS1' ) echo "selected='selected'"?>>주소검색 </option>
						 	<option value="EMAIL" <?php if ($paging->search_key == 'EMAIL' ) echo "selected='selected'"?>>이메일검색 </option>
						</select>
				   	
			   			<input type="text"  class="form-control" id="searchVal" name="searchVal" value="<?=$paging->search_val?>">
			   			<input type="button" class="form-control btn btn-warning" id="search" value="검색">
	
			 	  	</form>
				</div>
			</div>

	   	
			<table class="table table-striped table-hover table-responsive">
				<tr>
					<th>번호</th>
					<th>예약ID</th>
					<th>예약자</th>
					<th>룸타입</th>
					<th>룸번호</th>
					<th>시작일</th>
					<th>종료일</th>
					<th>예약일</th>
					<th>인원</th>
				</tr>
			
				<?php
				$return_url = rawurlencode($_SERVER['REQUEST_URI']);
				foreach ($reserves as $reserve) {
					
				?>
					<tr>
						<td><?=$reserve->RN?></td>
						<td><a href="<?=CONTEXT."/admin/reserve_detail?reserve_id={$reserve->ID}&return_url={$return_url}"?>">
								<?=$reserve->ID?></a></td>
						<td><?=$reserve->ACCOUNT?></td>
						<td><?=$reserve->TYPE_DESC?></td>
						<td><?=$reserve->ROOM_NUM?>호</td>
						<td><?=$reserve->DATE_START?></td>
						<td><?=$reserve->DATE_END?></td>
						<td><?=$reserve->DATE_RESERVE?></td>
						<td><?=$reserve->PAX?></td>
					</tr>
				<?php
				}
				?>
			</table>

		</div>
		<div class="panel-footer">
		
				<div class="text-center">
					<ul class="pagination">
						<?php
						if ($paging->page_start > $paging->page_max) {
						?>
							<li><a href="<?=CONTEXT."/admin/reserve_list?{$paging->make_query($paging->page_start - $paging->page_max)}"?>">&laquo;</a></li>
							
						<?php
						}
						
						for ($i = $paging->page_start; $i <= $paging->page_end; $i++) {
							$active = ''; 
							if ($paging->page_num == $i)
								$active = "class='active'";
						?>				
							<li <?=$active?>><a href="<?=CONTEXT."/admin/reserve_list?{$paging->make_query($i)}"?>" class="page"><?=$i?></a></li>
						<?php
						}
						
						if ($paging->page_end < $paging->page_count) {
						?>
							<li><a href="<?=CONTEXT."/admin/reserve_list?{$paging->make_query($paging->page_start + $paging->page_max)}"?>">&raquo;</a></li>				
						<?php
						}
						?>
						
					</ul>
				</div>
		</div>
	</div>
</div>

<script>
 
$('#search').on("click", function(event) {
	self.location = "<?=CONTEXT.'/admin/reserve_list'?>" +
					"?pageNum=1" +		
					"&searchKey=" + $("select option:selected").val() + 
					"&searchVal=" + $('#searchVal').val();
});
</script>
