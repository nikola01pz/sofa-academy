<?php

namespace Sofa\Homework\src\Entities;

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