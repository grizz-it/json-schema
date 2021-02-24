<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Component\Resolver;

use GrizzIt\JsonSchema\Common\ReferenceResolutionInterface;

class ReferenceResolution implements ReferenceResolutionInterface
{
    /** @var object|bool */
    private object | bool $schema;

    /** @var string|null */
    private ?string $identifier;

    /**
     * Constructor.
     *
     * @param object|bool $schema
     * @param string|null $identifier
     */
    public function __construct(object | bool $schema, ?string $identifier)
    {
        $this->schema = $schema;
        $this->identifier = $identifier;
    }

    /**
     * Returns the schema.
     *
     * @return object|bool
     */
    public function getSchema(): object | bool
    {
        return $this->schema;
    }

    /**
     * Returns the identifier.
     *
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }
}
