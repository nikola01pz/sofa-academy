<?php

declare(strict_types=1);

namespace SimpleFW\Autoload;

final class Autoloader
{
    private array $missingClasses = [];

    public function __construct(private readonly array $config)
    {
    }

    public static function createAndRegister(array $config, bool $prepend = true): self
    {
        $autoloader = new self($config);

        $autoloader->register($prepend);

        return $autoloader;
    }

    public function register(bool $prepend = true): void
    {
        spl_autoload_register([$this, 'autoload'], true, $prepend);
    }

    public function unregister(): void
    {
        spl_autoload_unregister([$this, 'autoload']);
    }

    /**
     * @param class-string $FQCN
     */
    public function autoload(string $FQCN): void
    {
        if (isset($this->missingClasses[$FQCN])) {
            return;
        }

        $namespacePrefix = null;
        foreach ($this->config as $namespace => $baseDir) {
            if (str_starts_with($FQCN, $namespace)) {
                $namespacePrefix = $namespace;
                break;
            }
        }

        if (null === $namespacePrefix) {
            $this->missingClasses[$FQCN] = true;

            return;
        }

        $className = str_replace($namespacePrefix, '', $FQCN);
        $filePath = $this->config[$namespacePrefix].\DIRECTORY_SEPARATOR.str_replace('\\', \DIRECTORY_SEPARATOR, $className).'.php';

        if (!file_exists($filePath)) {
            $this->missingClasses[$FQCN] = true;

            return;
        }

        require $filePath;
    }
}
