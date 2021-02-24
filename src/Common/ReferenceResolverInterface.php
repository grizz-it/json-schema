<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

use GrizzIt\JsonSchema\Component\Validator\ReferenceValidator;

interface ReferenceResolverInterface
{
    /**
     * Resolves the references.
     *
     * @param ReferenceValidator $reference
     *
     * @return ReferenceResolutionInterface|null
     */
    public function resolve(
        ReferenceValidator $reference
    ): ?ReferenceResolutionInterface;
}
