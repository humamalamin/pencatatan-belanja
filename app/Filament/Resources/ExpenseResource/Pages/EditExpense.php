<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Services\AIParserService;
use App\Services\OCRService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        if ($record->receipt_image) {
            $path = storage_path("app/public/{$record->receipt_image}");
            $ocr = new OCRService();
            $text = $ocr->extractTextFromImage($path);

            $record->note = $text;
            $record->save();
            dispatch(new \App\Jobs\AiParserJob($record));
        }
    }
}
