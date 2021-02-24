<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Common\ValidatorFactoryInterface;
use GrizzIt\JsonSchema\Factory\Validator\IterableValidatorFactory;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Factory\Validator\IterableValidatorFactory
 */
class IterableValidatorFactoryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::create
     */
    public function testCreate(): void
    {
        $validatorFactory = $this->createMock(ValidatorFactoryInterface::class);
        $validatorFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->createMock(ValidatorInterface::class));

        $subject = new IterableValidatorFactory($validatorFactory);

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create(
                (object)['foo'],
                (object)['bar'],
                (object)['baz'],
                1,
                2,
                false
            )
        );
    }
}
