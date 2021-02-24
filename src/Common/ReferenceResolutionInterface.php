<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

interface ReferenceResolutionInterface
{
    /**
     * Returns the schema.
     *
     * @return object|bool
     */
    public function getSchema(): object | bool;

    /**
     * Returns the identifier.
     *
     * @return string|null
     */
    public function getIdentifier(): ?string;
}
