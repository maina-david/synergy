<?php

namespace App\Jobs;

use App\Models\DocumentManagement\File;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessFileDelete implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected File $file)
    {
        $this->file = $file;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware(): array
    {
        return [
            Skip::when(function (): bool {
                return $this->shouldSkip();
            }),
        ];
    }

    private function shouldSkip(): bool
    {
        return $this->file->folder && $this->file->folder->isBeingMoved();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (Storage::exists($this->file->file_path)) {
                Storage::delete($this->file->file_path);
            }

            $this->file->delete();
        } catch (Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            throw new Exception('Failed to delete file.');
        }
    }
}