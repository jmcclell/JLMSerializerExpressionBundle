<?php

namespace TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JLM\SerializerExpressionBundle\Tests\Fixtures\Model\AllExclusionPolicy;
use JLM\SerializerExpressionBundle\Tests\Fixtures\Model\NoneExclusionPolicy;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ModelController extends FOSRestController
{
    /**
     * @Route("/getAllPolicy")
     */
    public function getAllPolicyAction()
    {
        $data = new AllExclusionPolicy();
        $view = $this->view($data, 200)
                    ->setTemplate('TestBundle:Model:getModel.html.twig');
        ;
        return $this->handleView($view);
    }

    /**
     * @Route("/getNonePolicy")
     */
    public function getNonePolicyAction()
    {
        $data = new NoneExclusionPolicy();
        $view = $this->view($data, 200)
            ->setTemplate('TestBundle:Model:getModel.html.twig');
        ;
        return $this->handleView($view);
    }
}