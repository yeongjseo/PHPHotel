function isNull(str) {
    if (str == null) return true;
    if (str == "NaN") return true;
    if (new String(str).valueOf() == "undefined") return true;    
    var chkStr = new String(str);
    if( chkStr.valueOf() == "undefined" ) return true;
    if (chkStr == null) return true;    
    if (chkStr.toString().length == 0 ) return true;   
    return false; 
}
 
function commonForm(opt_formId) {
    this.formId = isNull(opt_formId) == true ? "commonForm" : opt_formId;
    this.url = "";
     
    if(this.formId == "commonForm") {
        $("#commonForm")[0].reset();
        // by reset(), hidden isn't reset.
        // add below code
        $("#commonForm").children("[type=hidden]").remove();
    }
     
    this.setUrl = function setUrl(url) {
        this.url = url;
    };
     
    this.addParam = function addParam(key, value) {
        $("#"+this.formId).append($("<input type='hidden' name='"+key+"' id='"+key+"' value='"+value+"' >"));
    };
     
    this.submit = function submit() {
        var frm = $("#"+this.formId)[0];
        frm.action = this.url;
        frm.method = "post";
        frm.submit();   
    };
    
    this.send = function send() {
    	var frm = $("#"+this.formId)[0];
        frm.action = this.url;
        frm.method = "get";
        frm.submit();
    }
}

function isImageFile(fileName) {
	var pattern = /jpg|gif|png|jpeg/i;
	return fileName.match(pattern);
}

function getFileInfo(filename) {
	var fileName,imgsrc, getLink;
	var fileLink;
	var imgSrc = '${pageContext.request.contextPath}';

	// TODO
	if (isImageFile(filename)) {
		thumbSrc += ("/images/room/" + filename); 
		orgSrc += ("/images/room/" + filename);
		expName = filename; 
		
	} else {
		thumbSrc += "/images/icons/regFile.png";
		orgSrc += "/images/icons/regFile.png";
		expName = filename;
	}
	
	return {thumbSrc:thumbSrc, orgSrc:orgSrc, expName:expName};
}

function sleep(milliseconds) {
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds){
			break;
		}
	}
}

function checkTel(obj){
	var num_check=/^[0-9]*$/;
	if (num_check.test(obj.value)) {
		if (obj.value.length < 9 || obj.value.length > 11 ) {
			obj.placeholder = "유효한 전화번호 형식이 아닙니다.";
			obj.value = "";
			obj.style = "border-color:red";
			return false;
		}
	}
	else {
		console.log("숫자만 입력할 수 있습니다.");
		obj.placeholder = "숫자만 입력할 수 있습니다.";
		obj.value = "";
		obj.style = "border-color:red";
		return false;
	}
	obj.style = "background-color:";
	return true;
}

function checkEmail(obj) {
	var email = obj.value;  
	var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;   		  
	if (! regex.test(email)) {
		obj.value = "";
		obj.placeholder = "이메일 형식이 아닙니다.";
		obj.style = "border-color:red";
		return false;
	}
	obj.style = "background-color:";
	return true;
}

function checkMemberForm(frm) {
	if (frm.ACCOUNT.value == '') {
		openModalInfo('알림', '아이디를 입력하세요');
		return false;
	}
	if (frm.PASSWORD.value == '') {
		openModalInfo('알림', '비밀번호를 입력하세요');
		return false;
	}
	if (frm.NICKNAME.value == '') {
		openModalInfo('알림', '별명을 입력하시오');
		return false;
	}
	if (frm.EMAIL.value == '') {
		openModalInfo('알림', '이메일을 입력하시오');
		return false;
	}
	if (frm.TEL.value == '') {
		openModalInfo('알림', '전화번호을 입력하시오');
		return false;
	}
	if (frm.ZIPCODE.value == '') {
		openModalInfo('알림', '우편번호를 입력하시오');
		return false;
	}
	if (frm.ADDRESS1.value == '') {
		openModalInfo('알림', '주소를 입력하시오');
		return false;
	}
	if (frm.ADDRESS2.value == '') {
		openModalInfo('알림', '상세주소를 입력하시오');
		return false;
	}
	return true;
	
}



function execDaumPostcode(zipcodeObj, address1Obj, address2Obj) {
	console.log(zipcodeObj);
	new daum.Postcode({
		oncomplete: function(data) {
			var fullAddr = '';
			var extraAddr = '';
			
			if (data.userSelectedType === 'R') 
				fullAddr = data.roadAddress;
			else 
				fullAddr = data.jibunAddress;

			if(data.userSelectedType === 'R') {
				if (data.bname !== '') 
					extraAddr += data.bname;
				if (data.buildingName !== '') 
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// old postcode
			// zipcodeObj.value = data.postcode;
			zipcodeObj.value = data.zonecode;
			address1Obj.value = fullAddr;
			address2Obj.focus();
		}
	}).open();
}

function roomTypeDesc(roomType) {
	var desc = [
	    '에러', 
		'표준', 
		'디럭스', 
		'트윈-디럭스', 
		'슈피리어', 
		'럭셔리'
	];
	return desc[roomType];
}


function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for (var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}


function getPopupOption(x, y, w, h) {
	return "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no," + 
			"scrollbars=no,resizable=no," + 
			"top=" + y + ",left=" + x + ",width="+w + ",height=" + h;
}

function getPopupCenterOption(w, h){ 
	var wh = document.body.clientHeight;
	var ww = document.body.clientWidth;
	var wx = window.screenX || window.screenLeft || 0; 
	var wy = window.screenY || window.screenTop || 0; 

	var x = wx + (ww - w)/2;
	var y = wy + (wh - h)/2;
	return getPopupOption(x, y, w, h);
}











$(function() {
	$("input:text").keydown(function(evt) {
		if (evt.keyCode == 13)
			return false;
	});
});




