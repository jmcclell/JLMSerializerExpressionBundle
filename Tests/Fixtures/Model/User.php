<?php

namespace JLM\SerializerExpressionBundle\Tests\Fixtures\Model;

use JLM\SerializerExpression\Annotation\ExcludeIf;

class User
{

    
    public $firstName = 'Jason';
    public $lastName = 'McClellan';
    /**
     * @ExcludeIf("!secure(""has_role('ROLE_ADMIN')"")")
     * @var string
     */
    public $phone = '555-555-5555';
    /**
     * @ExcludeIf("!secure(""has_role('ROLE_USER')"")")
     * @var string
     */
    public $address = 'New York, NY';
    public $occupation = 'Software';
}