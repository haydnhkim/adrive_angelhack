<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Errorcode
	{
		/**
		 * 클래스 생성 시 호출
		 * 
		 * @Params none
		 * @Return void
		 */
		public function __construct()
		{
			define('CODE_SUCCESS', '0000'); // 성공
			define('CODE_FAILED', '9999'); // 성공
			
			define('CODE_REQUIRED_AUTHENTICATION', '4444'); // API 인증필요
			
			define('CODE_AUTH_FAILED', '9000'); // API 인증실패
			define('CODE_ERROR_DATABASE', '9001'); // DATABASE 오류
			define('CODE_NOT_AVAILABLE', '9002'); // 처리할 요청 없음
			define('CODE_DENIED', '9003'); // 처리 요청 거부
			define('CODE_ALREADY_PROCESSED', '9004'); // 이미 처리된 요청
			define('CODE_REQUEST_INVALID', '9005'); // 잘못된 요청
			
			define('CODE_VALUE_MISSING', '1000'); // 필수인자누락
			define('CODE_VALUE_INVALID', '1001'); // 잘못된 값
			define('CODE_VALUE_DUPLICATED', '1002'); // 중복된 값
			
			define('CODE_API_DEPRECATED', '8888'); // Deprecated API
			define('CODE_RESOURCE_NOTFOUND', '2000'); // 존재하지 않는 항목

			define('CODE_ACCESS_DENIED', '4000');
			
			define('CODE_UNKNOWN', '6666'); // 알수없는 에러가 발생했을 경우
		}
		
	}