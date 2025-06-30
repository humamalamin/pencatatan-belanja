<?php

use App\Services\OCRService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/test-ocr', function () {
    $ocr = new OCRService();
    $text = $ocr->extractTextFromImage(storage_path('app/public/test-struk.png'));
    dd($text);
});
