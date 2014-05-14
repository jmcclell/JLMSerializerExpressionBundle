<?php

namespace JLM\SerializerExpressionBundle\View;

use FOS\RestBundle\View as BaseViewHandler;

use JLM\SerializerExpression\Exclusion\ExpressionBasedExclusionStrategy;

class ViewHandler extends BaseViewHandler
{
    protected $strategy;

    public function __construct(ExpressionBasedExclusionStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function getSerializationContext(View $view)
    {
        $context = parent::getSerializationContext($view);
        $context->addExclusionStrategy($this->strategy);
        return $context;
    }
    
}