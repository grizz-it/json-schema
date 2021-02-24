<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Factory\Validator\TextualValidatorFactory;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Factory\Validator\TextualValidatorFactory
 */
class TextualValidatorFactoryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::create
     */
    public function testCreate(): void
    {
        $subject = new TextualValidatorFactory();

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create(
                1,
                2,
                'foo'
            )
        );
    }
}
