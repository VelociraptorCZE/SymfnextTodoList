<?php
declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Todo;

class TodoTransformer
{
    public function transform(Todo $todo): array
    {
        return [
            'description' => $todo->getDescription(),
            'createdAt' => $todo->getCreatedAt()->format('j. n. Y')
        ];
    }
}