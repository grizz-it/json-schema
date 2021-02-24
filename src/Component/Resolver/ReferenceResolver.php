<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Component\Resolver;

use GrizzIt\JsonSchema\Exception\SchemaException;
use GrizzIt\JsonSchema\Common\StorageManagerInterface;
use GrizzIt\JsonSchema\Common\ReferenceResolverInterface;
use GrizzIt\JsonSchema\Common\ReferenceResolutionInterface;
use GrizzIt\JsonSchema\Component\Resolver\ReferenceResolution;
use GrizzIt\JsonSchema\Component\Validator\ReferenceValidator;

class ReferenceResolver implements ReferenceResolverInterface
{
    private const TILDE_TRANSLATE = [
        ['~0', '~1'],
        ['~', '/'],
    ];

    /** @var StorageManagerInterface */
    private StorageManagerInterface $storageManager;

    /**
     * Constructor
     *
     * @param StorageManagerInterface $storageManager
     */
    public function __construct(StorageManagerInterface $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    /**
     * Resolves the references.
     *
     * @param ReferenceValidator $reference
     *
     * @return ReferenceResolutionInterface|null
     *
     * @throws SchemaException When the reference can not be resolved.
     */
    public function resolve(
        ReferenceValidator $reference
    ): ?ReferenceResolutionInterface {
        $schemaStorage = $this->storageManager->getSchemaStorage();

        if (!$reference->isResolved()) {
            $ref = $reference->getReference();
            $expRef = explode('#', $ref);
            $id = null;
            if (!$schemaStorage->has($expRef[0])) {
                $schemaStorage->set(
                    $expRef[0],
                    json_decode(file_get_contents($expRef[0]))
                );

                $id = $expRef[0];
            }

            $schema = $schemaStorage->get($expRef[0]);

            if (isset($expRef[1])) {
                $inReference = array_filter(
                    explode('/', trim($expRef[1], '/')),
                    function (string $ref): bool {
                        return $ref !== '';
                    }
                );

                foreach ($inReference as $inRef) {
                    $inRef = str_replace(
                        static::TILDE_TRANSLATE[0],
                        static::TILDE_TRANSLATE[1],
                        urldecode($inRef)
                    );

                    if (
                        is_numeric($inRef)
                        && array_key_exists($inRef, $schema)
                    ) {
                        $schema = $schema[$inRef];
                        continue;
                    } elseif (
                        is_object($schema)
                        && property_exists($schema, $inRef)
                    ) {
                        $schema = $schema->{$inRef};
                        continue;
                    }

                    throw new SchemaException(
                        sprintf(
                            'Could not resolve reference: %s',
                            $ref
                        )
                    );
                }
            }

            return new ReferenceResolution($schema, $id);
        }

        return null;
    }
}
