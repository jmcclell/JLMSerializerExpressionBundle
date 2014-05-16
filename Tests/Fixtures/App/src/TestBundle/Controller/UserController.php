<?php

namespace TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JLM\SerializerExpressionBundle\Tests\Fixtures\Model\User;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends FOSRestController
{
    /**
     * @Route("/getUser")     
     */
    public function getUserAction()
    {
        $data = new User;
        $view = $this->view($data, 200)
                    ->setTemplate('TestBundle:User:getUser.html.twig');
        ;
        return $this->handleView($view);
    }
}