<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions"%>  
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt"%>
<%@ include file="/common/include.jspf" %>

<style>
textarea { resize:none; height:200px;}
</style>

<div class="container">

	<form class="form-horizontal" method="post" action="boardDetail.do" id="form1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="text-center">
					에러페이지
				</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-sm-2">에러코드</label>
					<div class="col-sm-10">
						<textarea class="form-control" name="errorParam" rows="10" readonly>
HTTP상태코드 : 403에러
금지된 요청입니다.
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>