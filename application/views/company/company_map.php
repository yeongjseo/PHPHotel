
<!-- 네이버 클라이언트 아이디는 테스용입니다. -->
<script type="text/javascript" src="http://openapi.map.naver.com/openapi/v2/maps.js?clientId=1kpsfTZcBsnvPGJguvNT"></script>
    
<div class="container">
	<div class="col-sm-10 col-sm-offset-1">
		<div class="panel panel-primary">
		 	<div class="panel-heading">
		 		<h2 class="text-center">회사위치</h2>
		 	</div>
			<div class="panel-body">
				<div id=testMap></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	try {
    	document.execCommand('BackgroundImageCache', false, true);
	} catch (e) {}
	// 위치: 거구장  
    var oPoint = new nhn.api.map.LatLng(37.552670, 126.937826);
    nhn.api.map.setDefaultPoint('LatLng');
    oMap = new nhn.api.map.Map('testMap' ,{
	    point : oPoint,
	    zoom : 10,
	    enableWheelZoom : true,
	    enableDragPan : true,
	    enableDblClickZoom : false,
	    mapMode : 0,
	    activateTrafficMap : false,
	    activateBicycleMap : false,
	    minMaxLevel : [ 1, 14 ],
	    size : new nhn.api.map.Size(900, 400)
    });
    var oSize = new nhn.api.map.Size(28, 37);
    var oOffset = new nhn.api.map.Size(14, 37);
    var oIcon = new nhn.api.map.Icon('http://static.naver.com/maps2/icons/pin_spot2.png', oSize, oOffset);
       
    var oMarker = new nhn.api.map.Marker(oIcon, { title : 'S호텔' }); 
    oMarker.setPoint(oPoint);
    oMap.addOverlay(oMarker);
     
    var oLabel = new nhn.api.map.MarkerLabel(); 
    oMap.addOverlay(oLabel); 
    oLabel.setVisible(true, oMarker);
</script>
