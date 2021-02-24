<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Component\Map;

use GrizzIt\JsonSchema\Common\AbstractMap;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\Validator\Component\Logical\ConstValidator;
use GrizzIt\JsonSchema\Factory\Validator\TypeValidatorFactory;
use GrizzIt\JsonSchema\Factory\Validator\ChainValidatorFactory;
use GrizzIt\JsonSchema\Factory\Validator\ObjectValidatorFactory;
use GrizzIt\JsonSchema\Factory\Validator\LogicalValidatorFactory;
use GrizzIt\JsonSchema\Factory\Validator\NumericValidatorFactory;
use GrizzIt\JsonSchema\Factory\Validator\TextualValidatorFactory;
use GrizzIt\JsonSchema\Factory\Validator\IterableValidatorFactory;

class Draft7 extends AbstractMap
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            [
                IterableValidatorFactory::class => [
                    'items',
                    'additionalItems',
                    'contains',
                    'minItems',
                    'maxItems',
                    'uniqueItems'
                ],
                NumericValidatorFactory::class => [
                    'minimum',
                    'maximum',
                    'exclusiveMinimum',
                    'exclusiveMaximum',
                    'multipleOf'
                ],
                ObjectValidatorFactory::class => [
                    'properties',
                    'patternProperties',
                    'propertyNames',
                    'additionalProperties',
                    'dependencies',
                    'required',
                    'minProperties',
                    'maxProperties'
                ],
                TypeValidatorFactory::class => ['type'],
                LogicalValidatorFactory::class => [
                    'enum',
                    'if',
                    'then',
                    'else',
                    'not'
                ],
                TextualValidatorFactory::class => [
                    'minLength',
                    'maxLength',
                    'pattern'
                ],
                ChainValidatorFactory::class => [
                    'oneOf',
                    'anyOf',
                    'allOf'
                ]
            ]
        );
    }

    /**
     * Additional schema factory option.
     *
     * @param object|bool $schema
     *
     * @return ValidatorInterface|null
     */
    public function create(object | bool $schema): ?ValidatorInterface
    {
        if (is_object($schema) && property_exists($schema, 'const')) {
            return new ConstValidator($schema->const);
        }

        return null;
    }
}
