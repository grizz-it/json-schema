<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\JsonSchema\Factory\Validator;

use GrizzIt\Validator\Common\ValidatorInterface;
use GrizzIt\Validator\Component\Chain\AndValidator;
use GrizzIt\JsonSchema\Common\AbstractValidatorFactory;
use GrizzIt\Validator\Component\Iterable\ItemsValidator;
use GrizzIt\Validator\Component\Logical\AlwaysValidator;
use GrizzIt\Validator\Component\Iterable\ContainsValidator;
use GrizzIt\Validator\Component\Iterable\MaxItemsValidator;
use GrizzIt\Validator\Component\Iterable\MinItemsValidator;
use GrizzIt\Validator\Component\Iterable\UniqueItemsValidator;

class IterableValidatorFactory extends AbstractValidatorFactory
{
    /**
     * Composes the iterable validator.
     *
     * @param mixed     $items
     * @param mixed     $additionalItems
     * @param mixed     $contains
     * @param int|null  $minItems
     * @param int|null  $maxItems
     * @param bool|null $uniqueItems
     *
     * @return ValidatorInterface
     */
    public function create(
        mixed $items,
        mixed $additionalItems,
        mixed $contains,
        ?int $minItems,
        ?int $maxItems,
        ?bool $uniqueItems
    ): ValidatorInterface {
        $validators = [];
        $validatorFactory = $this->getValidatorFactory();

        if ($items !== null || $additionalItems !== null) {
            $validators[] = new ItemsValidator(
                $items === null ? null : (
                    is_array($items)
                        ? array_map(
                            function ($item) use (
                                $validatorFactory
                            ): ValidatorInterface {
                                return $validatorFactory->create($item, false);
                            },
                            $items
                        ) : $validatorFactory->create($items, false)
                ),
                $additionalItems === null
                    ? new AlwaysValidator(true)
                    : $validatorFactory->create($additionalItems, false)
            );
        }

        if ($contains !== null) {
            $validators[] = new ContainsValidator(
                $validatorFactory->create($contains, false)
            );
        }

        if ($minItems !== null) {
            $validators[] = new MinItemsValidator($minItems);
        }

        if ($maxItems !== null) {
            $validators[] = new MaxItemsValidator($maxItems);
        }

        if ($uniqueItems !== null) {
            $validators[] = new UniqueItemsValidator($uniqueItems);
        }

        return new AndValidator(...$validators);
    }
}
