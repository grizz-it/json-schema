<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Common\ValidatorFactoryInterface;
use GrizzIt\JsonSchema\Factory\Validator\ChainValidatorFactory;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Factory\Validator\ChainValidatorFactory
 */
class ChainValidatorFactoryTest extends TestCase
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

        $subject = new ChainValidatorFactory($validatorFactory);

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create(['foo'], ['bar'], ['baz'])
        );
    }
}
