<?php

namespace App\Enums\Admin;

enum StorageType: string
{
    case LOCAL = 'local';
    case S3 = 's3';
}