<?php

declare(strict_types=1);

namespace SimpleFW\Templating;

use SimpleFW\Templating\Exception\FileNotFoundException;

final readonly class Templating
{
    public function __construct(
        private string $basePath,
    ) {
    }

    public function render(string $template, array $variables = []): string
    {
        // @TODO: Zadatak 2
        $templatesPath = $this->basePath.\DIRECTORY_SEPARATOR.$template;
        if (!file_exists($templatesPath)) {
            throw new FileNotFoundException($templatesPath);
        }

        ob_start();
        extract($variables);
        include $templatesPath;
        return ob_get_clean();

    }
}
