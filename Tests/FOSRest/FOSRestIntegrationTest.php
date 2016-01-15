<?php

namespace JLM\SerializerExpressionBundle\Tests\FOSRest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session;

class JLMSerializerExpressionExtensionTest extends WebTestCase
{

    /**
     * @dataProvider securitySerializationDataProvider
     */
    public function testSerializationView($auth, $policy,  $expectedFields)
    {
        // For the integration test we are using the users configured in config.yml rather than hacking the security object with our own token here
        if ($auth == null) {
            $auth = array();
        } else {
            $auth = array('PHP_AUTH_USER' => $auth,
                          'PHP_AUTH_PW' => 'password');
        }
        $client = static::createClient(array(), $auth);

        $client->request('GET', "/get${policy}Policy");
        $response = $client->getResponse();
        $responseContent = $response->getContent();

        $this->assertNotEmpty($responseContent, "Response should not be empty. Probably an auth error - check configuration.");

        $data = json_decode($responseContent, true);
        $fields = array_keys($data);
        sort($fields);
        sort($expectedFields);
        $this->assertEquals($expectedFields, $fields);
    }

    public function securitySerializationDataProvider()
    {
        return array(
            array(null, 'None', array('b', 'd', 'e', 'f', 'g', 'h', 'i', 'bb', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('user', 'None', array('b', 'c', 'e', 'f', 'g', 'h', 'i', 'bb', 'cc', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('admin', 'None',  array('a', 'c', 'e', 'f', 'g', 'h', 'i', 'aa', 'cc', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array(null, 'All', array('a', 'c', 'aa', 'cc', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('user', 'All', array('a', 'd', 'aa', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii')),
            array('admin', 'All',  array('b', 'd', 'bb', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii'))
        );
    }
}