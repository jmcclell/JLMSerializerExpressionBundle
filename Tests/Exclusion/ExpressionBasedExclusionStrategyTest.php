<?php

namespace JLM\SerializerExpressionBundle\Tests\Exclusion;

use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ExpressionBasedExclusionStrategyTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testExpressionBasedExclusionStrategyExists()
    {
        $client = $this->client;
        $container = $client->getContainer();
        
        $this->assertTrue($container->has('jlm_serializer_expression.expression_based_exclusion_strategy'));

        $strategy = $container->get('jlm_serializer_expression.expression_based_exclusion_strategy');

        $this->assertFalse(is_null($strategy));   
    }

    /**
     * @dataProvider securitySerializationDataProvider
     */
    public function testSecuritySerialization($roles, $policy, $expectedFields)
    {
        $this->login($roles);
        $client = $this->client;
        $container = $client->getContainer();
        $strategy = $container->get('jlm_serializer_expression.expression_based_exclusion_strategy');
        $serializer = SerializerBuilder::create()
            ->configureListeners(function(EventDispatcher $dispatcher) use ($strategy) {
               $dispatcher->addSubscriber($strategy);
            })
            ->build();
        $context = SerializationContext::create();
        $context->addExclusionStrategy($strategy);

        $class = "JLM\\SerializerExpressionBundle\\Tests\\Fixtures\\Model\\";
        $class = $class . $policy . 'ExclusionPolicy';
        $data = json_decode($serializer->serialize(new $class, 'json', $context), true);
        $fields = array_keys($data);
        sort($fields);
        sort($expectedFields);
        $this->assertEquals($expectedFields, $fields);

        foreach ($fields as $field) {
            $this->assertTrue(in_array($field, $expectedFields));
        }     
    }

    public function securitySerializationDataProvider()
    {
        return array(
            array('ROLE_ANONYMOUS', 'None', array('b', 'd', 'e', 'f', 'g', 'h', 'i', 'bb', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('ROLE_USER', 'None', array('b', 'c', 'e', 'f', 'g', 'h', 'i', 'bb', 'cc', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array(array('ROLE_USER', 'ROLE_ADMIN'), 'None',  array('a', 'c', 'e', 'f', 'g', 'h', 'i', 'aa', 'cc', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('ROLE_ANONYMOUS', 'All', array('a', 'c', 'aa', 'cc', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('ROLE_USER', 'All', array('a', 'd', 'aa', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array(array('ROLE_ADMIN', 'ROLE_USER'), 'All',  array('b', 'd', 'bb', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii'))
        );
    }

    public function login($roles)
    {
        $roles = (array)$roles;
        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('user', null, $firewall, $roles);
        $this->client->getContainer()->get('security.token_storage')->setToken($token);
    }
}