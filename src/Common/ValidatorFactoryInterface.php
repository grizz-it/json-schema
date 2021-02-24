<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

use GrizzIt\Validator\Common\ValidatorInterface;

interface ValidatorFactoryInterface
{
    /**
     * Composes the schema validator.
     *
     * @param object|bool $schema
     * @param bool        $isBase
     * @param string      $overrideId
     *
     * @return ValidatorInterface
     */
    public function create(
        object | bool $schema,
        bool $isBase = true,
        string $overrideId = null
    ): ValidatorInterface;
}
