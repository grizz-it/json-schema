<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Factory;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Factory\Validator\TypeValidatorFactory;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Factory\Validator\TypeValidatorFactory
 */
class TypeValidatorFactoryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::create
     * @covers ::createTypeValidator
     */
    public function testCreate(): void
    {
        $subject = new TypeValidatorFactory();

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create('object')
        );

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create(['object', 'boolean'])
        );

        $this->expectException(InvalidArgumentException::class);

        $subject->create('foo');
    }
}
