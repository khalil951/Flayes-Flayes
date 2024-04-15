<?php

// src/Form/DataTransformer/StringToFileTransformer.php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // Transform string to File object
        return new File($value);
    }

    public function reverseTransform($value)
    {
        // Transform File object to string
        return $value->getPathname();
    }
}
