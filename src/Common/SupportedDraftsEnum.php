<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Common;

use GrizzIt\Enum\Enum;
use GrizzIt\JsonSchema\Component\Map\Draft7;

/**
 * @method static SupportedDraftsEnum DEFAULT()
 * @method static SupportedDraftsEnum DRAFT_07()
 * @method static SupportedDraftsEnum DRAFT_06()
 */
class SupportedDraftsEnum extends Enum
{
    public const DRAFT_06 = Draft7::class;
    public const DRAFT_07 = Draft7::class;
    public const DEFAULT = SupportedDraftsEnum::DRAFT_07;
}
