<?php
namespace App\Portfolio;

use Framework\Upload;

class PortfolioUpload extends Upload
{
    protected $path = 'public' . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR .'portfolio';

    protected $formats = [
        'thumb' => [200, 200]
    ];
}