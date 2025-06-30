<?php

namespace App\Jobs;

use App\Models\Expense;
use App\Services\AIParserService;
use App\Services\Helper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AiParserJob implements ShouldQueue
{
    use Queueable;

    private $record;

    /**
     * Create a new job instance.
     */
    public function __construct(Expense $record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $helper = app(Helper::class);
        $ai = new AIParserService();
        $parsed = $ai->parseWithAI($this->record->note);
        $this->record->date_shopping = $parsed['date'] ?? null;
        $this->record->amount = $parsed['total'] ?? 0;
        $this->record->parsed_data = $parsed['items'] ?? [];

        $dataClean = $helper->extractSpecialFieldsAndCleanItems($parsed['items'] ?? []);
        $this->record->change = $dataClean['extractedFields']['kembalian'] ?? 0;
        $this->record->vat = $dataClean['extractedFields']['vat'] ?? 0;

        $this->record->save();

        foreach ($dataClean['cleanedItems'] ?? [] as $item) {
            $this->record->items()->create($item);
        }
    }
}
