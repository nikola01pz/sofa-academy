<?php

namespace Sofa\Homework;

class Sport
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $id,
        public array $tournaments,
    ) {

    }
}