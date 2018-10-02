<?php
namespace App\Cv;

use Framework\Upload;

class SkillUpload extends Upload
{
    protected $path =
        'public' . DIRECTORY_SEPARATOR .
        'uploads'. DIRECTORY_SEPARATOR .
        'skills'. DIRECTORY_SEPARATOR .
        'logos';
}
