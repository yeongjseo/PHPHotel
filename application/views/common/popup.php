<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions"%>  
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt"%>
<%@ include file="/common/include.jspf" %>

<style>
body {
	padding: 0 !important;
}
</style>

<body>
	<div class="panel panel-primary">
		<div class="panel-heading text-center">
			<h4>[<c:out value="${boardDTO.getBoardTypeLongDesc(boardType)}"/>]
				<span>${boardDTO.title}</span>
			</h4>
		</div>
		<div class="panel-body">
			<div class="thumbnail">
				<c:forEach items="${boardFileDTOList}" var="file" begin="0" end="0" varStatus="var">
					<img class="img-responsive img-rounded" 
						src="imageDisplay.do?fileId=${file.id}&type=${boardType}" alt="Image">
				</c:forEach>
			</div>
			<div>
				<span>${boardDTO.content}</span>
			</div>
		</div>
		<div class="panel-footer">
			<div class="checkbox">
  				<label><input type="checkbox" value="" id="popupNo">오늘 다시 열지 않기</label>
			</div>
			<input type="button" class="form-control btn btn-warning" id="close" value="닫기">
		</div>
	</div>
</body>

<script>

$("#popupNo").on("click", function(ev) {
	setCookie("${boardType}PopupNo", "true", 1);
	self.close();
});

$("#close").on("click", function(ev) {
	self.close();
});
</script>