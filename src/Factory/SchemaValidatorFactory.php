<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Factory;

use InvalidArgumentException;
use GrizzIt\JsonSchema\Common\MapInterface;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Exception\SchemaException;
use GrizzIt\JsonSchema\Common\SupportedDraftsEnum;
use GrizzIt\JsonSchema\Common\StorageManagerInterface;
use GrizzIt\JsonSchema\Common\ValidatorFactoryInterface;
use GrizzIt\JsonSchema\Component\Storage\StorageManager;
use GrizzIt\JsonSchema\Factory\Validator\ValidatorFactory;
use GrizzIt\JsonSchema\Common\SchemaValidatorFactoryInterface;

class SchemaValidatorFactory implements SchemaValidatorFactoryInterface
{
    /** @var StorageManagerInterface */
    private StorageManagerInterface $storageManager;

    /** @var MapInterface */
    private MapInterface $map;

    /** @var ValidatorFactoryInterface */
    private ValidatorFactoryInterface $validatorFactory;

    /**
     * Constructor
     *
     * @param SupportedDraftsEnum        $map
     * @param StorageManagerInterface    $storageManager
     * @param ReferenceResolverInterface $referenceResolver
     */
    public function __construct(
        SupportedDraftsEnum $map = null,
        StorageManagerInterface $storageManager = null,
        ValidatorFactoryInterface $validatorFactory = null
    ) {
        $map = !is_null($map)
            ? (string) $map
            : (string) SupportedDraftsEnum::DEFAULT();

        $this->map = new $map();
        $this->storageManager = $storageManager ?? new StorageManager();
        $this->validatorFactory = $validatorFactory ?? new ValidatorFactory(
            $this->map,
            $this->storageManager
        );
    }

    /**
     * Composes the schema validator.
     *
     * @param object|bool|array $schema
     * @param string $schemaPath
     *
     * @return ValidatorInterface
     */
    public function create(
        object | bool | array $schema,
        string $schemaPath = ''
    ): ValidatorInterface {
        return $this->validatorFactory->create(
            $schema,
            true,
            null,
            $schemaPath
        );
    }

    /**
     * Creates a validator which is first verified against the defined $schema.
     *
     * @param object $schema
     * @param string $schemaPath
     *
     * @return ValidatorInterface
     *
     * @throws SchemaException When the $schema property isn't set.
     * @throws SchemaException When the schema itself is invalid.
     */
    public function createVerifiedValidator(
        object $schema,
        string $schemaPath = ''
    ): ValidatorInterface {
        if (!property_exists($schema, '$schema')) {
            throw new SchemaException(
                'The $schema property must be set for verified validators.'
            );
        }

        $validator = $this->createFromRemoteFile($schema->{'$schema'});
        if ($validator($schema)) {
            return $this->create($schema, $schemaPath);
        }

        throw new SchemaException('The schema was invalid!');
    }

    /**
     * Creates the validator from a file path.
     *
     * @param string $path
     *
     * @return ValidatorInterface
     *
     * @throws InvalidArgumentException When the file can not be found.
     */
    public function createFromLocalFile(string $path): ValidatorInterface
    {
        $oldPath = $path;
        $path = realpath($path);
        if (file_exists($path)) {
            return $this->createFromRemoteFile($path);
        }

        throw new InvalidArgumentException(
            sprintf(
                'Could not find file %s.',
                $path
            )
        );
    }

    /**
     * Creates the validator from a file path.
     *
     * @param string $path
     *
     * @return ValidatorInterface
     *
     * @throws InvalidArgumentException When the file can not be retrieved.
     */
    public function createFromRemoteFile(string $path): ValidatorInterface
    {
        $schemaStorage = $this->storageManager->getSchemaStorage();
        $validatorStorage = $this->storageManager->getValidatorStorage();
        if ($validatorStorage->has($path)) {
            return $validatorStorage->get($path);
        }

        if ($schemaStorage->has($path)) {
            $schema = $schemaStorage->get($path);
            $validatorStorage->set(
                $path,
                $this->create($schema, $path)
            );

            return $validatorStorage->get($path);
        }

        return $this->createFromString(file_get_contents($path), null, $path);
    }

    /**
     * Creates a validator from a string.
     *
     * @param string $json
     * @param string $id
     * @param string $schemaPath
     *
     * @return ValidatorInterface
     *
     * @throws SchemaException When the supplied JSON is invalid.
     */
    public function createFromString(
        string $json,
        string $id = null,
        string $schemaPath = ''
    ): ValidatorInterface {
        $schema = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SchemaException(
                sprintf(
                    'Could not prepare schema, invalid JSON supplied: %s.',
                    json_last_error_msg()
                )
            );
        }

        if (
            is_object($schema)
            && !property_exists($schema, '$id')
            && $id !== null
        ) {
            $schema->{'$id'} = $id;
        }

        if (!property_exists($schema, '$id') || empty($schema->{'$id'})) {
            $schema->{'$id'} = $schemaPath;
        }

        $this->storageManager
            ->getSchemaStorage()
            ->set($schema->{'$id'}, $schema);

        $validator = $this->create($schema, $schemaPath);

        $this->storageManager
            ->getValidatorStorage()
            ->set($schema->{'$id'}, $validator);

        return $validator;
    }
}
