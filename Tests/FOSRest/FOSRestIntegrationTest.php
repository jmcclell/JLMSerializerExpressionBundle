<?php

namespace JLM\SerializerExpressionBundle\Tests\FOSRest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use JLM\SerializerExpressionBundle\Tests\Fixtures\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session;

class JLMSerializerExpressionExtensionTest extends WebTestCase
{

    /**
     * @dataProvider securitySerializationDataProvider
     */
    public function testSerializationView($auth, $expectedFields)
    {
        // For the integration test we are using the users configured in config.yml rather than hacking the security object with our own token here
        if ($auth == null) {
            $auth = array();
        } else {
            $auth = array('PHP_AUTH_USER' => $auth,
                          'PHP_AUTH_PW' => 'password');
        }
        $client = static::createClient(array(), $auth);
        $container = $client->getContainer();

        $client->request('GET', '/getUser');
        $response = $client->getResponse();
        $responseContent = $response->getContent();

        $data = json_decode($responseContent, true);
        $fields = array_keys($data);
        $this->assertEquals(count($expectedFields), count($fields));

        foreach ($fields as $field) {
            $this->assertTrue(in_array($field, $expectedFields));
        }     
    }

    public function securitySerializationDataProvider()
    {
        return array(
            array(null, array('first_name', 'last_name', 'occupation')),
            array('user', array('first_name', 'last_name', 'occupation', 'address')),
            array('admin', array('first_name', 'last_name', 'occupation', 'address', 'phone'))
        );
    }
}