<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Factory\Validator\NumericValidatorFactory;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Factory\Validator\NumericValidatorFactory
 */
class NumericValidatorFactoryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::create
     */
    public function testCreate(): void
    {
        $subject = new NumericValidatorFactory();

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create(1, 2, 3, 4, 5)
        );
    }
}
