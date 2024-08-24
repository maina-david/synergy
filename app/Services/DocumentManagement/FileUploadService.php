<?php

namespace App\Services\DocumentManagement;

use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    /**
     * Create or update a folder if it exists.
     *
     * @param  string $folderName
     * @param  Folder|null $parentFolder
     * @return \App\Models\DocumentManagement\Folder
     */
    protected function createOrUpdateFolder(string $folderName, ?Folder $parentFolder = null): Folder
    {
        $folder = Folder::updateOrCreate(
            [
                'name' => $folderName,
                'parent_id' => $parentFolder?->id,
            ]
        );

        return $folder;
    }

    /**
     * Upload a file and store its metadata.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  Folder $folder
     * @param  string|null $description
     * @return \App\Models\DocumentManagement\File
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadFile(UploadedFile $file, Folder $folder, ?string $description = null): File
    {
        $this->validateFile($file);

        $filePath = $this->storeFile($file);

        $fileModel = $this->createFileRecord($file, $folder, $filePath, $description);

        return $fileModel;
    }

    /**
     * Validate the uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateFile(UploadedFile $file): void
    {
        $rules = [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ];

        $validator = Validator::make(['file' => $file], $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Store the file in the storage.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected function storeFile(UploadedFile $file): string
    {
        return $file->store('files', 'public');
    }

    /**
     * Create a file record and save it to the database.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  Folder $folder
     * @param  string $filePath
     * @param  string|null $description
     * @return \App\Models\DocumentManagement\File
     */
    protected function createFileRecord(UploadedFile $file, Folder $folder, string $filePath, ?string $description = null): File
    {
        return $folder->files()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $description,
        ]);
    }
}