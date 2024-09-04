<?php

namespace Database\Seeders;

use App\Models\DocumentManagement\File;
use App\Models\DocumentManagement\Folder;
use App\Models\Organization\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FolderAndFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::first();

        if ($organization) {
            for ($i = 0; $i < 5; $i++) {
                $folder = Folder::create([
                    'user_id' => $organization->users->first()->id,
                    'organization_id' => $organization->id,
                    'name' => "Folder $i"
                ]);

                $this->createSubfoldersAndFiles($folder, $organization, 1);
            }
        }
    }

    /**
     * Recursively create subfolders and files within a given folder.
     *
     * @param Folder $parentFolder
     * @param Organization $organization
     * @param int $currentLevel
     */
    private function createSubfoldersAndFiles(Folder $parentFolder, Organization $organization, int $currentLevel): void
    {
        $maxLevels = 4;

        if ($currentLevel > $maxLevels) {
            return;
        }

        for ($i = 0; $i < 3; $i++) {
            $subFolder = Folder::create([
                'user_id' => $organization->users->first()->id,
                'organization_id' => $organization->id,
                'name' => "Level {$currentLevel} Sub Folder $i",
                'parent_id' => $parentFolder->id,
            ]);

            for ($j = 0; $j < 2; $j++) {
                File::create([
                    'user_id' => $organization->users->first()->id,
                    'organization_id' => $organization->id,
                    'folder_id' => $subFolder->id,
                    'file_name' => "File_L{$currentLevel}$j" . Str::random(5) . ".txt",
                    'file_path' => "path/to/file_L{$currentLevel}$j" . Str::random(5) . ".txt",
                    'file_type' => 'txt',
                    'file_size' => rand(1000, 10000),
                    'description' => "This is a description for File_$j in Level {$currentLevel} Sub Folder $i.",
                ]);
            }

            $this->createSubfoldersAndFiles($subFolder, $organization, $currentLevel + 1);
        }
    }
}