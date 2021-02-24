<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

interface ReferenceTranslatorInterface
{
    /**
     * Translates the reference.
     *
     * @param string $schemaId
     * @param string $reference
     *
     * @return string
     */
    public function translate(string $schemaId, string $reference): string;
}
