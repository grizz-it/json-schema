<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

use GrizzIt\Validator\Common\ValidatorInterface;

interface SchemaValidatorFactoryInterface
{
    /**
     * Composes the schema validator.
     *
     * @param object|bool $schema
     *
     * @return ValidatorInterface
     */
    public function create(object | bool $schema): ValidatorInterface;

    /**
     * Creates the validator from a file path.
     *
     * @param string $path
     *
     * @return ValidatorInterface
     */
    public function createFromLocalFile(string $path): ValidatorInterface;

    /**
     * Creates the validator from a file path.
     *
     * @param string $path
     *
     * @return ValidatorInterface
     */
    public function createFromRemoteFile(string $path): ValidatorInterface;

    /**
     * Creates a validator from a string.
     *
     * @param string $json
     * @param string $id
     *
     * @return ValidatorInterface
     */
    public function createFromString(
        string $json,
        string $id = null
    ): ValidatorInterface;
}
