<?php

namespace App\Visitors;

use App\Interfaces\FileManagerInterface;

class FileConverterVisitor
{
    public FileManagerInterface $fileReader;

    public function __construct(FileManagerInterface $fileReader)
    {
        $this->fileReader = $fileReader;
    }
}