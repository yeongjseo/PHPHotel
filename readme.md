
### PHP 개발환경 설정

[http://suhlab.tistory.com/29](http://suhlab.tistory.com/29) 참고

---
### PHP 오라클 접속

***php.ini***

	; 12 인스턴트 클라이언트 사용
	extension=php_oci8_12c.dll
	; 메일 전송용
	extension=php_openssl.dll

***인스턴트 클라이언트 다운/복사*** 

* 다운로드 instantclient_12_1
* instantclient_12_1/bin/ 파일 모두 httpd/bin/으로 복사

***테스트 PHP 파일로 접속 테스트***
 
	<?php
	// Create connection to Oracle
	$tns2 = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 127.0.0.1)(PORT = 1521)) (CONNECT_DATA = (SID = XE)))";
	$conn = oci_connect("system", "oracle", $tns2);
	if (!$conn) {
	   $m = oci_error();
	   echo $m['message'], "\n";
	   exit;
	}
	else {
	   print "Connected to Oracle!";
	}
	
	// Close the Oracle connection
	oci_close($conn);
	?>

---
### Code Igniter 오라클(Oracle 11g XE) 설정

***application/config/development/database.php

	$tns = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 127.0.0.1)(PORT = 1521)) (CONNECT_DATA = (SERVICE_NAME  = XE)))";
	$db['oracle'] = array(
			'dsn'	=> '',
			 //'hostname' => 'localhost',
			'hostname' => $tns,
			'username' => 'system',
			'password' => 'oracle',
			'database' => '',
			'dbdriver' => 'oci8',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt'  => FALSE,
			'autoinit' => TRUE,
			'stricton' => FALSE,
	);



