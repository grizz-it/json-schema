<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Factory\Validator;

use GrizzIt\JsonSchema\Common\MapInterface;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Exception\SchemaException;
use GrizzIt\Validator\Component\Chain\AndValidator;
use GrizzIt\JsonSchema\Common\StorageManagerInterface;
use GrizzIt\JsonSchema\Common\ValidatorFactoryInterface;
use GrizzIt\Validator\Component\Logical\AlwaysValidator;
use GrizzIt\JsonSchema\Common\ReferenceResolverInterface;
use GrizzIt\JsonSchema\Common\ReferenceTranslatorInterface;
use GrizzIt\JsonSchema\Component\Resolver\ReferenceResolver;
use GrizzIt\JsonSchema\Component\Validator\ReferenceValidator;
use GrizzIt\JsonSchema\Component\Translator\ReferenceTranslator;

class ValidatorFactory implements ValidatorFactoryInterface
{
    /**
     * Contains the ID of the current schema.
     *
     * @var string
     */
    private string $id;

    /**
     * Determines if new references have been created.
     *
     * @var bool
     */
    private bool $newReferences = false;

    /**
     * Contains the last known path for the resolver.
     *
     * @var string
     */
    private string $lastKnownPath = '';

    /** @var ReferenceTranslatorInterface */
    private ReferenceTranslatorInterface $referenceTranslator;

    /** @var MapInterface */
    private MapInterface $map;

    /** @var StorageManagerInterface */
    private StorageManagerInterface $storageManager;

    /** @var ReferenceResolverInterface */
    private ReferenceResolverInterface $referenceResolver;

    /**
     * Constructor.
     *
     * @param MapInterface $map
     * @param StorageManagerInterface $storageManager
     * @param ReferenceResolverInterface $referenceResolver
     * @param ReferenceTranslatorInterface $referenceTranslator
     */
    public function __construct(
        MapInterface $map,
        StorageManagerInterface $storageManager,
        ReferenceResolverInterface $referenceResolver = null,
        ReferenceTranslatorInterface $referenceTranslator = null
    ) {
        $this->map = $map;
        $this->storageManager = $storageManager;
        $this->referenceResolver = $referenceResolver
            ?? new ReferenceResolver($this->storageManager);
        $this->referenceTranslator = $referenceTranslator
            ?? new ReferenceTranslator();
    }

    /**
     * Composes the schema validator.
     *
     * @param object|bool $schema
     * @param bool        $isBase
     * @param string      $overrideId
     * @param string      $schemaPath
     *
     * @return ValidatorInterface
     *
     * @throws SchemaException When the supplied schema is not a object or bool.
     */
    public function create(
        object | bool $schema,
        bool $isBase = true,
        string $overrideId = null,
        string $schemaPath = ''
    ): ValidatorInterface {
        $lastKnownPath = empty($schemaPath) ? $this->lastKnownPath : $schemaPath;
        if ($schemaPath !== '') {
            $this->lastKnownPath = $schemaPath;
        }

        if ($overrideId !== null) {
            $this->id = $overrideId;
        }

        if (is_bool($schema)) {
            return new AlwaysValidator($schema);
        }

        $this->prepareDefinitions($schema);

        if ($isBase) {
            $this->id = property_exists($schema, '$id')
                    ? $schema->{'$id'}
                    : uniqid('', true);

            $this->storageManager
                ->getSchemaStorage()
                ->set($this->id, $schema);
        }

        if (property_exists($schema, '$ref')) {
            $reference = $this->referenceTranslator->translate(
                $this->id,
                $schema->{'$ref'},
                $this->lastKnownPath
            );

            $this->newReferences = true;

            $referenceStorage = $this->storageManager->getReferenceStorage();
            if ($referenceStorage->has($reference)) {
                return $referenceStorage->get($reference);
            }

            $referenceValidator = new ReferenceValidator($reference);

            $referenceStorage->set($reference, $referenceValidator);

            if ($isBase) {
                $this->resolveReferences();
            }

            return $referenceValidator;
        }

        $validators = $this->schemaToValidators($schema);

        if ($isBase) {
            $this->resolveReferences();
        }

        if ($schemaPath !== '') {
            $this->lastKnownPath = $lastKnownPath;
        }

        return new AndValidator(...$validators);
    }

    /**
     * Converts the schema to a set of validators.
     *
     * @param object $schema
     *
     * @return ValidatorInterface[]
     */
    private function schemaToValidators(object $schema): array
    {
        $validators = [];
        foreach ($this->map as $factory => $map) {
            $arguments = [];
            foreach ($map as $property) {
                if (property_exists($schema, $property)) {
                    $arguments[] = $schema->{$property};

                    continue;
                }

                $arguments[] = null;
            }

            if (
                count(
                    array_filter(
                        $arguments,
                        function ($value) {
                            return !is_null($value);
                        }
                    )
                ) > 0
            ) {
                $validators[] = $this->map
                    ->getFactory($this, $factory)
                    ->create(...$arguments);
            }
        }

        $additionalValidator = $this->map->create($schema);
        if ($additionalValidator !== null) {
            $validators[] = $additionalValidator;
        }

        return $validators;
    }

    /**
     * Interprets the defintions which contain an ID.
     *
     * @param object $schema
     *
     * @return void
     */
    private function prepareDefinitions(object $schema): void
    {
        if (property_exists($schema, 'definitions')) {
            foreach ($schema->definitions as $definition) {
                if (
                    is_object($definition)
                    && property_exists($definition, '$id')
                ) {
                    $this->storageManager->getSchemaStorage()->set(
                        $definition->{'$id'},
                        $definition
                    );
                }
            }
        }
    }

    /**
     * Resolves the references from the schema.
     *
     * @return void
     */
    private function resolveReferences(): void
    {
        $this->newReferences = false;
        /** @var ReferenceValidator $reference */
        foreach ($this->storageManager->getReferenceStorage() as $reference) {
            $resolution = $this->referenceResolver->resolve($reference);
            if ($resolution !== null) {
                $reference->setValidator(
                    $this->create(
                        $resolution->getSchema(),
                        false,
                        $resolution->getIdentifier()
                    )
                );
            }
        }

        if ($this->newReferences === true) {
            $this->resolveReferences();
        }
    }
}
