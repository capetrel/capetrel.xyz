<?php
namespace App\Account;

use Framework\Upload;

class AccountUpload extends Upload
{
    protected $path =  'public'. DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatar';

    protected $formats = [
        'thumb' => [150, 150]
    ];
}
