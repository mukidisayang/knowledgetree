<?



require_once(dirname(__FILE__) . '/../test.php');
require_once(KT_DIR .  '/ktapi/ktapi.inc.php');


class APIAuthenticationTestCase extends KTUnitTestCase 
{
	function testAdmin()
	{
		$ktapi = new KTAPI();
		
		$session = $ktapi->start_session('admin','admin');
		$this->assertTrue(is_a($session,'KTAPI_UserSession'));
		$this->assertTrue($session->is_active());
		
		$session->logout();
		$this->assertFalse($session->is_active());
	}
	
	function testSystemLogin()
	{
		$ktapi = new KTAPI();
		
		$session = $ktapi->start_system_session();
		$this->assertTrue(is_a($session,'KTAPI_SystemSession'));
		$this->assertTrue($session->is_active());
		
		$session->logout();
		$this->assertFalse($session->is_active());
	}
	
	function testAnonymousLogin()
	{
		$ktapi = new KTAPI();
		
		$session = $ktapi->start_anonymous_session();
		$this->assertTrue(is_a($session,'KTAPI_AnonymousSession'));
		$this->assertTrue($session->is_active());
		
		$session->logout();
		$this->assertFalse($session->is_active());
	}
	
}
?>