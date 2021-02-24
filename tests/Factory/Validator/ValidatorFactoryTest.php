<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\JsonSchema\Common\MapInterface;
use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\JsonSchema\Exception\SchemaException;
use GrizzIt\JsonSchema\Common\SupportedDraftsEnum;
use GrizzIt\JsonSchema\Component\Storage\StorageManager;
use GrizzIt\JsonSchema\Factory\Validator\ValidatorFactory;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Factory\Validator\ValidatorFactory
 */
class ValidatorFactoryTest extends TestCase
{
    /**
     * @param mixed $schema
     * @param bool $isBase
     * @param string|null $overrideId
     *
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::schemaToValidators
     * @covers ::prepareDefinitions
     * @covers ::resolveReferences
     *
     * @dataProvider schemaProvider
     */
    public function testCreate(
        $schema,
        bool $isBase = false,
        string $overrideId = null
    ): void {
        $draft = (string) SupportedDraftsEnum::DRAFT_07();
        $subject = new ValidatorFactory(
            new $draft(),
            new StorageManager()
        );

        $this->assertInstanceOf(
            ValidatorInterface::class,
            $subject->create($schema, $isBase, $overrideId)
        );
    }

    /**
     * @return array
     */
    public function schemaProvider(): array
    {
        return [
            [
                json_decode(file_get_contents(__DIR__ . '/../../assets/Draft7.json')),
                true,
                'draft7'
            ],
            [
                (object) ['$ref' => 'http://json-schema.org/draft-07/schema#'],
                true,
                'draft7'
            ],
            [
                (object) [
                    'const' => ['foo'],
                    'definitions' => (object) [
                        'foo' => (object) [
                            '$id' => 'bar'
                        ]
                    ]
                ],
                true,
                'additionalValidator'
            ]
        ];
    }
}
