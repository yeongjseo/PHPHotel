<?php require_once VIEWPATH.'common/include.php' ?>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">
				<span class="sr-only">토글</span>
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=CONTEXT.'/main'?>">PHP호텔</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">소개<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=CONTEXT.'/company/intro'?>">소개</a></li>
						<li><a href="<?=CONTEXT.'/company/map'?>">위치</a></li>
						<li><a href="<?=CONTEXT.'/board/list?type=notice'?>">공지사항</a></li>
						<li><a href="<?=CONTEXT.'/board/list?type=event'?>">이벤트</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">룸/예약<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=CONTEXT.'/room/show_room_type'?>">룸소개</a></li>
						<li><a href="<?=CONTEXT.'/reserve/search'?>">예약</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">고객지원<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?=CONTEXT.'/company/contact'?>">문의하기</a></li>
					</ul>
				</li>
				<li><a href="<?=CONTEXT.'/board/list?type=guest'?>">방명록</a></li>
			</ul>
		 	<form class="nav navbar-nav navbar-right navbar-form">
			
				<?php
				$user = null;
				if (! ($user= $this->session->userdata('user'))) {
				?>
					<div class="form-group">
						<input type="text" name="account" id="account" class="form-control"  placeholder="아이디"> 
						<input type="text" name="password" id="password" class="form-control" placeholder="비밀번호">
						<button id="login" type="button" class="btn btn-default navbar-btn">로그인</button>
					</div>
						
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?=CONTEXT.'/member/join'?>"><span class="glyphicon glyphicon-user"></span>회원가입</a></li>
					</ul>
				<?php
				} else {
				?>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?=$user->ACCOUNT ?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?=CONTEXT.'/member/detail'?>">회원정보</a></li>	
							<li><a href="<?=CONTEXT.'/member/reserve_detail'?>">예약정보</a></li>
						</ul>
					</li>
				
					<?php
					if ($user->ID == 1) {
					?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">관리자메뉴<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?=CONTEXT.'/admin/member_list'?>">회원관리</a></li>
								<li><a href="<?=CONTEXT.'/admin/room_list'?>">룸관리</a></li>
								<li><a href="<?=CONTEXT.'/admin/reserve_list'?>">예약관리</a></li>
								<li><a href="<?=CONTEXT.'/admin/reserve_calendar'?>">예약달력</a></li>
							</ul>
						</li>
					<?php
					}
					?>
					<li><a href="<?=CONTEXT.'/member/logout'?>"><span class="glyphicon glyphicon-log-out"></span>로그아웃</a></li>
				<?php
				}
				?>
			</form>
		</div>
	</div>
</nav>



<script>


$('#login').click(function() {
	if ($('#account').val() == '') {
		openModalInfo("확인", "아이디를 입력하시오");
		return;
	}
	
	if ($('#password').val() == '') {
		openModalInfo("확인", "비밀번호를 입력하시오");
		return;
	}
	
	$.post('<?=CONTEXT.'/member/login'?>', 
		{
       		account: $('#account').val(),
       		password: $('#password').val(),
        },
        function(data, status) {
            // alert(data);
            // [object]
			if (data.result == "success") {
				// self.location = "<?=CONTEXT.'/main'?>";
				//self.location = "<?=current_url()?>";
				self.location = "<?=$_SERVER['REQUEST_URI']?>";
			}
			else {
				openModalError("로그인 실패", data.reason);
			}
        }
	);
})


</script>
