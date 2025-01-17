<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Service\Anonymize\Anonymizer;

use Opengento\Gdpr\Service\Anonymize\AnonymizerInterface;

/**
 * Class NullValue
 */
final class NullValue implements AnonymizerInterface
{
    /**
     * @inheritdoc
     */
    public function anonymize($value)
    {
        return null;
    }
}
