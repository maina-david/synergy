<?php

namespace App\Services\DocumentManagement;

use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

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
        return Folder::updateOrCreate(
            [
                'name' => $folderName,
                'parent_id' => $parentFolder?->id,
            ]
        );
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

        $filePath = $this->storeFile($file, $folder);

        return $this->createFileRecord($file, $folder, $filePath, $description);
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
     * Store the file in the specified folder.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  Folder $folder
     * @return string
     */
    protected function storeFile(UploadedFile $file, Folder $folder): string
    {
        $folderPath = $folder->id ? "folders/{$folder->id}" : 'files';

        $disk = Storage::disk('public');
        if (!$disk->exists($folderPath)) {
            $disk->makeDirectory($folderPath);
        }

        return $file->store($folderPath, 'public');
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

    /**
     * Retrieve file metadata by ID.
     *
     * @param  int $fileId
     * @return \App\Models\DocumentManagement\File|null
     */
    public function getFile(int $fileId): ?File
    {
        return File::find($fileId);
    }

    /**
     * Delete a file from storage and database.
     *
     * @param  int $fileId
     * @return bool
     * @throws \Exception
     */
    public function deleteFile(int $fileId): bool
    {
        $file = $this->getFile($fileId);

        if (!$file) {
            throw new \Exception("File not found.");
        }

        // Delete the file from storage
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        // Delete the file record from database
        return $file->delete();
    }

    /**
     * Rename an existing file.
     *
     * @param  int $fileId
     * @param  string $newName
     * @return \App\Models\DocumentManagement\File
     * @throws \Exception
     */
    public function renameFile(int $fileId, string $newName): File
    {
        $file = $this->getFile($fileId);

        if (!$file) {
            throw new \Exception("File not found.");
        }

        $file->file_name = $newName;
        $file->save();

        return $file;
    }

    /**
     * Move a file to a different folder.
     *
     * @param  int $fileId
     * @param  Folder $newFolder
     * @return \App\Models\DocumentManagement\File
     * @throws \Exception
     */
    public function moveFile(int $fileId, Folder $newFolder): File
    {
        $file = $this->getFile($fileId);

        if (!$file) {
            throw new \Exception("File not found.");
        }

        $newPath = $this->storeFileAs($file, $newFolder);

        // Update file path in database
        $file->file_path = $newPath;
        $file->save();

        return $file;
    }

    /**
     * Store the file in a new location based on the folder.
     *
     * @param  \App\Models\DocumentManagement\File $file
     * @param  Folder $folder
     * @return string
     */
    protected function storeFileAs(File $file, Folder $folder): string
    {
        $disk = Storage::disk('public');
        $oldPath = $file->file_path;
        $newFolderPath = $folder->id ? "folders/{$folder->id}" : 'files';
        $newPath = $newFolderPath . '/' . basename($oldPath);

        // Check if new directory exists
        if (!$disk->exists($newFolderPath)) {
            $disk->makeDirectory($newFolderPath);
        }

        // Move the file to the new directory
        $disk->move($oldPath, $newPath);

        return $newPath;
    }
}