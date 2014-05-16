<?php

namespace JLM\SerializerExpressionBundle\Tests\Exclusion;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use JLM\SerializerExpressionBundle\Tests\Fixtures\Model\User;
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
    public function testSecuritySerialization($roles, $expectedFields)
    {
        $this->login($roles);
        $client = $this->client;
        $container = $client->getContainer();
        $strategy = $container->get('jlm_serializer_expression.expression_based_exclusion_strategy');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $context = \JMS\Serializer\SerializationContext::create();
        $context->addExclusionStrategy($strategy);

        $data = json_decode($serializer->serialize(new User, 'json', $context), true);
        $fields = array_keys($data);
        $this->assertEquals(count($expectedFields), count($fields));

        foreach ($fields as $field) {
            $this->assertTrue(in_array($field, $expectedFields));
        }     
    }

    public function securitySerializationDataProvider()
    {
        return array(
            array('ROLE_ANONYMOUS', array('first_name', 'last_name', 'occupation')),
            array('ROLE_USER', array('first_name', 'last_name', 'occupation', 'address')),
            array(array('ROLE_USER', 'ROLE_ADMIN'), array('first_name', 'last_name', 'occupation', 'address', 'phone'))
        );
    }

    public function login($roles)
    {
        $roles = (array)$roles;
        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('user', null, $firewall, $roles);
        $this->client->getContainer()->get('security.context')->setToken($token);
    }
}