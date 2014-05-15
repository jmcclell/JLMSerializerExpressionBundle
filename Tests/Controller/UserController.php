<?php

namespace JLM\SerializerExpressionBundle\Tests\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JLM\SerializerExpressionBundle\Tests\Model;

class UserController extends FOSRestController
{
    public function getUsersAction()
    {
        $data = array(new User);
        $view = $this->view($data, 200);
        return $this->handleView($view);
    }