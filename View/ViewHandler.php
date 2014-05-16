<?php

namespace JLM\SerializerExpressionBundle\View;

use FOS\RestBundle\View\ViewHandler as BaseViewHandler;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

use JLM\SerializerExpression\Exclusion\ExpressionBasedExclusionStrategy;

class ViewHandler extends BaseViewHandler
{
    protected $strategy;

    public function __construct(
        ExpressionBasedExclusionStrategy $strategy,
        array $formats = null,
        $failedValidationCode = Codes::HTTP_BAD_REQUEST,
        $emptyContentCode = Codes::HTTP_NO_CONTENT,
        $serializeNull = false,
        array $forceRedirects = null,
        $defaultEngine = 'twig'
    ) {
        $this->strategy = $strategy;
        parent::__construct($formats, $failedValidationCode, $emptyContentCode, $serializeNull, $forceRedirects, $defaultEngine);
    }

    public function getSerializationContext(View $view)
    {
        $context = parent::getSerializationContext($view);
        $context->addExclusionStrategy($this->strategy);
        return $context;
    }
    
}