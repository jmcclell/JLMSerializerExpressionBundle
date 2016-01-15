<?php

namespace JLM\SerializerExpressionBundle\Tests\Fixtures\Model;

use JLM\SerializerExpression\Annotation\ExcludeIf;
use JLM\SerializerExpression\Annotation\ExposeIf;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ExclusionPolicy("ALL")
 *
 * ExcludeIf annotations are ignored because of the ALL exclusion policy (we can only
 * expose with this policy since everything is excluded by default.)
 */
class AllExclusionPolicy
{
    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - excluded
     *
     * @ExposeIf("!secure(""has_role('ROLE_ADMIN')"")")
     */
    public $a = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - exposed
     *
     * @ExposeIf("secure(""has_role('ROLE_ADMIN')"")")
     */
    public $b = true;

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     * @ExposeIf("!secure(""has_role('ROLE_USER')"")")
     */
    public $c = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @ExposeIf("secure(""has_role('ROLE_USER')"")")
     */
    public $d = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     * @ExcludeIf("!secure(""has_role('ROLE_ADMIN')"")")
     */
    public $e = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     * @ExcludeIf("secure(""has_role('ROLE_ADMIN')"")")
     */
    public $f = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     * @ExcludeIf("!secure(""has_role('ROLE_USER')"")")
     */
    public $g = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     * @ExcludeIf("secure(""has_role('ROLE_USER')"")")
     */
    public $h = true;

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     */
    public $i = true;

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - excluded
     *
     * @VirtualProperty()
     * @ExposeIf("!secure(""has_role('ROLE_ADMIN')"")")
     */
    public function aa() { return true; }

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - excluded
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     * @ExposeIf("secure(""has_role('ROLE_ADMIN')"")")
     */
    public function bb() { return true; }

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - excluded
     * ROLE_ADMIN - excluded
     *
     * @VirtualProperty()
     * @ExposeIf("!secure(""has_role('ROLE_USER')"")")
     */
    public function cc() { return true; }

    /**
     * ANONYMOUS - excluded
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     * @ExposeIf("secure(""has_role('ROLE_USER')"")")
     */
    public function dd() { return true; }

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     * @ExcludeIf("!secure(""has_role('ROLE_ADMIN')"")")
     */
    public function ee() { return true; }

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     * @ExcludeIf("secure(""has_role('ROLE_ADMIN')"")")
     */
    public function ff() { return true; }

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     * @ExcludeIf("!secure(""has_role('ROLE_USER')"")")
     */
    public function gg() { return true; }

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     * @ExcludeIf("secure(""has_role('ROLE_USER')"")")
     */
    public function hh() { return true; }

    /**
     * ANONYMOUS - exposed
     * ROLE_USER - exposed
     * ROLE_ADMIN - exposed
     *
     * @VirtualProperty()
     */
    public function ii() { return true; }
}