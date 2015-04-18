<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/21/15
 * Time: 10:23 PM
 */
use ORN\Auth;

// Running from the CLI doesn't set $_SESSION
if (!isset($_SESSION)) {
    $_SESSION = array();
}

/**
 * Auth Class test
 */
class AuthTest extends PHPUnit_Framework_TestCase {
    /**
     * @var array backup of $_SESSION, see above
     */
    private $phpSession;

    /**
     * restore the session
     */
    protected function setUp()
    {
        $_SESSION = $this->phpSession;
    }

    /**
     * save the session
     */
    protected function tearDown()
    {
        $this->phpSession = $_SESSION;
    }

    /**
     * login basically works
     */
    function testLogin() {
        global $config;
        $username = $config['user']['username'];
        $password = $config['user']['password'];

        $auth = new Auth(true);
        $this->assertTrue($auth->login($username, $password));
        $this->assertTrue($auth->authenticate());
        $auth->logout();
    }

    /**
     * wrong password does not authenticate
     */
    function testAuthenticateInvalid() {
        global $config;
        $username = $config['user']['username'];
        $password = $config['user']['password'] . 'random_junk';

        $auth = new Auth(true);
        $this->assertFalse($auth->login($username, $password));
        $this->assertFalse($auth->authenticate());
        $auth->logout();
    }

    /**
     * logout works
     */
    function testLogout() {
        global $config;
        $username = $config['user']['username'];
        $password = $config['user']['password'];

        $auth = new Auth(true);

        $auth->login($username, $password);
        $this->assertTrue($auth->authenticate());

        $auth->logout();
        $this->assertFalse(isset($_SESSION['username']));
    }
}
