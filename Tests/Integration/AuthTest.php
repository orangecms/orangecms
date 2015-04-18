<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/21/15
 * Time: 10:23 PM
 */

/**
 * Auth Class test
 */
class AuthIntegrationTest extends PHPUnit_Framework_TestCase {
    /**
     * @var \Behat\Mink\Driver\GoutteDriver
     */
    private $driver;

    /**
     * @var \Behat\Mink\Session
     */
    private $session;

    /**
     * start a browser session
     */
    function setUp()
    {
        $this->driver = new \Behat\Mink\Driver\GoutteDriver();
        $this->session = new \Behat\Mink\Session($this->driver);
        $this->session->start();
    }

    /**
     * stop the browser session
     */
    function tearDown()
    {
        $this->session->stop();
    }

    /**
     * login basically works
     */
    function testLogin()
    {
        global $config;
        $username = $config['user']['username'];
        $password = $config['user']['password'];

        /* visit login page */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $this->assertContains(
            'Please log in.',
            $page->getContent()
        );

        /* send password to /login and check */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $page->findField('username')->setValue($username);
        $page->findField('password')->setValue($password);
        $this->driver->submitForm('//*[@id="login-form"]');

        $this->assertContains(
            'Hello ' . $username . '! :)',
            $page->getContent()
        );
    }

    /**
     * wrong password does not authenticate
     */
    function testAuthenticateInvalid()
    {
        global $config;
        $username = $config['user']['username'];
        $password = $config['user']['password'] . 'random_junk';

        /* visit login page */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $this->assertContains(
            'Please log in.',
            $page->getContent()
        );

        /* send password to /login and check */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $page->findField('username')->setValue($username);
        $page->findField('password')->setValue($password);
        $this->driver->submitForm('//*[@id="login-form"]');

        $this->assertContains(
            'Login failed.',
            $page->getContent()
        );

    }

    /**
     * logout works
     */
    function testLogout()
    {
        global $config;
        $username = $config['user']['username'];
        $password = $config['user']['password'];

        /* visit login page */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $this->assertContains(
            'Please log in.',
            $page->getContent()
        );

        /* send password to /login and check */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $page->findField('username')->setValue($username);
        $page->findField('password')->setValue($password);
        $this->driver->submitForm('//*[@id="login-form"]');

        $this->assertContains(
            'Hello ' . $username,
            $page->getContent()
        );

        /* visit /logout */
        $this->session->visit(BASE_URL . 'login?logout');
        $page = $this->session->getPage();

        $this->assertContains(
            'You have been logged out.',
            $page->getContent()
        );

        /* visit login page again */
        $this->session->visit(BASE_URL . 'login');
        $page = $this->session->getPage();

        $this->assertContains(
            'Please log in.',
            $page->getContent()
        );

    }
}
