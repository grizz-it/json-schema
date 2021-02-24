<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

/**
 * An abstract implementation to tag a factory which requires the main
 * validator factory.
 */
abstract class AbstractValidatorFactory
{
    /** @var ValidatorFactoryInterface */
    private ValidatorFactoryInterface $validatorFactory;

    /**
     * Constructor.
     *
     * @param ValidatorFactoryInterface $validatorFactory
     */
    public function __construct(ValidatorFactoryInterface $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Retrieve the validator factory.
     *
     * @return ValidatorFactoryInterface
     */
    public function getValidatorFactory(): ValidatorFactoryInterface
    {
        return $this->validatorFactory;
    }
}
