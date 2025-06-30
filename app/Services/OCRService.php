<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRService
{
    public function extractTextFromImage(string $path): string
    {
        return (new TesseractOCR($path))
            ->lang('ind+eng')
            ->run();
    }
}
