<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Component\Storage;

use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\JsonSchema\Common\StorageManagerInterface;

class StorageManager implements StorageManagerInterface
{
    /**
     * Contains the schema storage.
     *
     * @var StorageInterface
     */
    private StorageInterface $schemaStorage;

    /**
     * Contains the validator storage.
     *
     * @var StorageInterface
     */
    private StorageInterface $validatorStorage;

    /**
     * Contains the reference storage.
     *
     * @var StorageInterface
     */
    private StorageInterface $referenceStorage;

    /**
     * Constructor
     *
     * @param StorageInterface $schemaStorage
     * @param StorageInterface $validatorStorage
     * @param StorageInterface $referenceStorage
     */
    public function __construct(
        StorageInterface $schemaStorage = null,
        StorageInterface $validatorStorage = null,
        StorageInterface $referenceStorage = null
    ) {
        $this->schemaStorage = $schemaStorage ?? new ObjectStorage();
        $this->validatorStorage = $validatorStorage ?? new ObjectStorage();
        $this->referenceStorage = $referenceStorage ?? new ObjectStorage();
    }

    /**
     * Retrieves the schema storage.
     *
     * @return StorageInterface
     */
    public function getSchemaStorage(): StorageInterface
    {
        return $this->schemaStorage;
    }

    /**
     * Retrieves the validator storage.
     *
     * @return StorageInterface
     */
    public function getValidatorStorage(): StorageInterface
    {
        return $this->validatorStorage;
    }

    /**
     * Retrieves the reference storage.
     *
     * @return StorageInterface
     */
    public function getReferenceStorage(): StorageInterface
    {
        return $this->referenceStorage;
    }
}
