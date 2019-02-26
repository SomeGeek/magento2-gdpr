<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Service\Export\Renderer;

use Opengento\Gdpr\Service\Export\AbstractRenderer;

/**
 * Class PdfRenderer
 */
final class PdfRenderer extends AbstractRenderer
{
    /**
     * {@inheritdoc}
     */
    public function render(array $data): string
    {
        // todo use htmlRenderer + wkhtmltopdf
        throw new \LogicException('Not implemented yet!');
    }
}
