
<div class="container">
	<div class="panel panel-primary">
  		<div class="panel-heading">
  			<div class="text-center">
  				<h4>멤버 리스트</h4>
  			</div>
  		</div>
	
		<div class="panel-body">
		   	<div class="centered">
			<form class="form-inline">
		   		<select name="searchKey" class="form-control">
		  			<option value="" selected="selected">전체검색</option>
				 	<option value="ACCOUNT" <?php if ($paging->search_key == 'ACCOUNT' ) echo "selected='selected'"?>>계정명검색</option>
				 	<option value="TEL" <?php if ($paging->search_key == 'TEL' ) echo "selected='selected'"?>>연락처</option>
				 	<option value="ADDRESS1" <?php if ($paging->search_key == 'ADDRESS1' ) echo "selected='selected'"?>>주소검색 </option>
				 	<option value="EMAIL" <?php if ($paging->search_key == 'EMAIL' ) echo "selected='selected'"?>>이메일검색 </option>
		 	   	  	
		   		</select>
		   		<input type="text"  class="form-control" id="searchVal" name="searchVal" value="<?=$paging->search_val?>">
		   		<input type="button" class="form-control" id="search" value="검색">
		   	</form>
		   	</div>
		   	
			<table class="table table-striped table-hover table-responsive">
				<tr>
					<th>번호</th>
					<th>고유번호</th>
					<th>계정명</th>
					<th>비밀번호</th>
					<th>사용자명</th>
					<th>생일</th>
					<th>우편번호</th>
					<th>주소</th>
					<th>상세주소</th>
					<th>이메일</th>
					<th>연락처</th>
					<th>삭제</th>
				</tr>
			
				<?php
				foreach ($members as $member) {
				?>
					<tr>
						<td><?=$member->RN?></td>
						<td><?=$member->ID?></td>
						<td><a href="<?=CONTEXT."/admin/member_detail?id={$member->ID}&{$paging->make_query()}"?>"><?=$member->ACCOUNT?></a></td>
						<td><?=$member->PASSWORD?></td>
						<td><?=$member->NICKNAME?></td>
						<td><?=$member->BIRTHDAY?></td>
						<td><?=$member->ZIPCODE?></td>
						<td><?=$member->ADDRESS1?></td>
						<td><?=$member->ADDRESS2?></td>
						<td><?=$member->EMAIL?></td>
						<td><?=$member->TEL?></td>
						<td><button type="button" class="btn btn-danger btn-xs delete" data-id="<?=$member->ID?>">삭제</button></td>
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
							<li><a href="<?=CONTEXT."/admin/member_list?{$paging->make_query($paging->page_start - $paging->page_max)}"?>">&laquo;</a></li>
							
						<?php
						}
						
						for ($i = $paging->page_start; $i <= $paging->page_end; $i++) {
							$active = ''; 
							if ($paging->page_num == $i)
								$active = "class='active'";
						?>				
							<li <?=$active?>><a href="<?=CONTEXT."/admin/member_list?{$paging->make_query($i)}"?>" class="page"><?=$i?></a></li>
						<?php
						}
						
						if ($paging->page_end < $paging->page_count) {
						?>
							<li><a href="<?=CONTEXT."/admin/member_list?{$paging->make_query($paging->page_start + $paging->page_max)}"?>">&raquo;</a></li>				
						<?php
						}
						?>
						
					</ul>
				</div>
			</div>
		
		
	</div>
		

</div>

<script>
$('#search').on("click", function(ev) {
	self.location = "<?=CONTEXT.'/admin/member_list'?>" +
				"?page_num=1" +
				"&search_key=" + $("select option:selected").val() + 
				"&search_val=" + $('#searchVal').val();						
});

$('.delete').on("click", function(ev) {
	var frm = new commonForm();
	frm.setUrl("<?=CONTEXT.'/admin/member_delete'?>");
	frm.addParam("ID", $(this).attr("data-id"));
	frm.addParam("page_num", "<?=$paging->page_num?>");
	frm.addParam("search_key", "<?=$paging->search_key?>");
	frm.addParam("search_val", "<?=$paging->search_val?>");
	frm.submit();
});

				
</script>


