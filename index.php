<?php

/************************************************************/
/*  ____ ____ ____ ____ ____ ____ _________ ____ ____ ____  */
/* ||O |||r |||a |||n |||g |||e |||       |||C |||M |||S || */
/* ||__|||__|||__|||__|||__|||__|||_______|||__|||__|||__|| */
/* |/__\|/__\|/__\|/__\|/__\|/__\|/_______\|/__\|/__\|/__\| */
/*                                                          */
/************************************************************/
/*                          About                           */
/* This is Orange CMS, a [C]ontent [M]anagement [S]ystem to */
/* easily create your own little self-hosted blog using the */
/* [O]pen [R]SS Feed [a]nd [N]ews Page [G]enerator [E]ngine */
/* written by Daniel Maslowski. It is tiny, quick & orange. */
/*                                                          */
/************************************************************/
/*                         Credits                          */
/* For ASCII arts fonts (the one used above is smkeyboard): */
/* http://artii.herokuapp.com/                              */
/* For the IDEA plugin by jonstaff, see his GitHub page:    */
/* https://github.com/jonstaff/IdeaAscii                    */
/*                                                          */
/************************************************************/

namespace ORN; // __NAMESPACE__

use ORN\Output\HTMLOutput;
use ORN\Output\OutputInterface;
use ORN\Output\RSSOutput;
use ORN\REST\Login;
use ORN\REST\Posts;
use ORN\REST\RESTInterface;
use ORN\REST\Tags;

//define('DEBUG',   true);
define('DEBUG',   false);
//define('VERBOSE', true);
define('VERBOSE', false);

require_once('autoload.php');
require_once('password.php');
require_once('headers.php');
require_once('config.php');

function init()
{
    /* handle files */
//    var_dump($_FILES);

    /* handle route */
    $route = explode('/', strtok(getenv('REQUEST_URI'), '?'));
    // first element is an empty string, so it must be removed
    array_shift($route);
    if (DEBUG && VERBOSE) var_dump(getenv('REQUEST_URI'), $route);

    /* handle request parameters */
    $params = $_GET;
    if (DEBUG && VERBOSE) var_dump('original params: ', $params);

    /* handle request body */
    $inputJSON = file_get_contents('php://input');
    $inputArray = json_decode($inputJSON, true);
    $data = null;
    if (is_array($inputArray)) {
        $data = $inputArray;
    } else {
        $headers = getallheaders();
        if (isset($headers['Content-Type']) &&
            $headers['Content-Type'] == 'application/json') {
            $data = array();
        } else {
            $data = $_POST;
        }
    }
    if (DEBUG && VERBOSE) var_dump($data);

    /* initialize controller */
    /**@var $ctrl RESTInterface|OutputInterface*/
    $ctrl = null;
    /* the first part of the route maps to the controller */
    switch (array_shift($route)) {
        case 'blog'  : $ctrl = new HTMLOutput(); break;
        case 'rss'   : $ctrl = new RSSOutput();  break;
        case 'posts' : $ctrl = new Posts();      break;
        case 'tags'  : $ctrl = new Tags();       break;
        case 'login' : $ctrl = new Login();      break;
    }
    if (DEBUG) var_dump($route);

    if ($ctrl instanceof RESTInterface) {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET'    : $ctrl->get($route, $params);         break;
            case 'POST'   : $ctrl->post($route, $params, $data); break;
            case 'PUT'    : $ctrl->put($route, $params, $data);  break;
            case 'DELETE' : $ctrl->delete($route, $data);        break;
        }
    } else if ($ctrl instanceof OutputInterface) {
        if ($ctrl->prepare($route, $params)) {
            $ctrl->out();
        } else {

        }
    }

    return true;
}

init();
