<?php
namespace App\Blog;

use Framework\Upload;

class PostUpload extends Upload
{
    protected $path = 'public' . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR .'posts';

    protected $formats = [
        'thumb' => [320, 180]
    ];
}
