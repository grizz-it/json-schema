<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Tests\Component\Translator;

use PHPUnit\Framework\TestCase;
use GrizzIt\JsonSchema\Exception\SchemaException;
use GrizzIt\JsonSchema\Component\Translator\ReferenceTranslator;

/**
 * @coversDefaultClass GrizzIt\JsonSchema\Component\Translator\ReferenceTranslator
 * @covers GrizzIt\JsonSchema\Exception\SchemaException
 */
class ReferenceTranslatorTest extends TestCase
{
    /**
     * @param string $schemaId,
     * @param string $reference
     * @param string $expected
     *
     * @return void
     *
     * @covers ::translate
     *
     * @dataProvider translatorProvider
     */
    public function testTranslate(
        string $schemaId,
        string $reference,
        string $expected,
        string $schemaPath
    ): void {
        $subject = new ReferenceTranslator();

        $this->assertEquals(
            $expected,
            $subject->translate($schemaId, $reference, $schemaPath)
        );
    }

    /**
     * @return void
     *
     * @covers ::translate
     */
    public function testTranslateException(): void
    {
        $subject = new ReferenceTranslator();
        $this->expectException(SchemaException::class);
        $subject->translate('/test/', 'foo/');
    }

    /**
     * @return string[][]
     */
    public function translatorProvider(): array
    {
        return [
            [
                'http://json-schema.org/draft-07/schema#',
                '#/definitions/schemaArray',
                'http://json-schema.org/draft-07/schema#/definitions/schemaArray',
                ''
            ],
            [
                '#',
                'http://json-schema.org/draft-07/schema#',
                'http://json-schema.org/draft-07/schema#',
                ''
            ],
            [
                'http://json-schema.org/draft-07/schema#/definitions/nonNegativeInteger',
                '/defintions/schemaArray',
                'http://json-schema.org/draft-07/schema#/defintions/schemaArray',
                ''
            ],
            [
                'http://json-schema.org/draft-07/schema/definitions/nonNegativeInteger',
                '/definitions/schemaArray',
                'http://json-schema.org/definitions/schemaArray',
                ''
            ],
            [
                'schema.json',
                'Draft7.json',
                realpath(__DIR__ . '/../../assets/Draft7.json'),
                __DIR__ . '/../../assets/schema.json'
            ],
            [
                'schema.json',
                realpath(__DIR__ . '/../../assets/Draft7.json'),
                realpath(__DIR__ . '/../../assets/Draft7.json'),
                ''
            ],
        ];
    }
}
