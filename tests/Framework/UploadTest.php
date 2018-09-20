<?php
namespace Tests\Framework;

use Framework\Upload;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;

class UploadTest extends TestCase
{

    /**
     * @var Upload
     */
    private $upload;

    protected function setUp()
    {
        $this->upload = new Upload('tmp' . DIRECTORY_SEPARATOR . 'upload');
    }

    protected function tearDown()
    {
        $file = 'tmp' . DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR .'demo.jpg';

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function testUpload()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();

        $uploadedFile->expects($this->any())
            ->method('getError')
            ->willReturn(UPLOAD_ERR_OK);

        $uploadedFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo.jpg');

        $uploadedFile->expects($this->once())
            ->method('moveTo')
            ->with($this->equalTo('tmp\upload\demo.jpg'));

        $this->assertEquals('demo.jpg', $this->upload->upload($uploadedFile));
    }

    public function testUploadWithExistingFile()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();

        $uploadedFile->expects($this->any())
            ->method('getError')
            ->willReturn(UPLOAD_ERR_OK);

        $uploadedFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo.jpg');

        touch('tmp' . DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR .'demo.jpg');

        $uploadedFile->expects($this->once())
            ->method('moveTo')
            ->with($this->equalTo('tmp\upload\demo_copy.jpg'));

        $this->assertEquals('demo_copy.jpg', $this->upload->upload($uploadedFile));
    }

    public function testDontMoveIfFileNotUploaded()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFileInterface::class)->getMock();

        $uploadedFile->expects($this->any())
            ->method('getError')
            ->willReturn(UPLOAD_ERR_CANT_WRITE);

        $uploadedFile->expects($this->any())
            ->method('getClientFilename')
            ->willReturn('demo.jpg');

        $uploadedFile->expects($this->never())
            ->method('moveTo')
            ->with($this->equalTo('tmp\upload\demo.jpg'));

        $this->assertNull($this->upload->upload($uploadedFile));
    }

    public function testDoNothingIfFileNotUploaded()
    {
        $file = $this->getMockBuilder(UploadedFileInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $file->method('getError')->willReturn(UPLOAD_ERR_CANT_WRITE);
        $file->expects($this->never())->method('moveTo');
        $this->upload->upload($file);
    }

    public function testCreateFormats()
    {
        @unlink('./tmp/upload/demo.png');
        @unlink('./tmp/upload/demo_thumb.png');
        $file = $this->getMockBuilder(UploadedFileInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $file->method('getError')->willReturn(UPLOAD_ERR_OK);
        $file->method('getClientFileName')->willReturn('demo.png');
        $file->expects($this->once())->method('moveTo')->willReturnCallback(function () {
            imagepng(imagecreatetruecolor(1000, 1000), './tmp/upload/demo.png');
        });
        // On crée un faux format
        $property = (new \ReflectionClass($this->upload))->getProperty('formats');
        $property->setAccessible(true);
        $property->setValue($this->upload, ['thumb' => [100, 200]]);
        // On s'attend à obtenir une image miniature
        $this->upload->upload($file);
        $this->assertArraySubset([100, 200], getimagesize('./tmp/upload/demo_thumb.png'));
        $this->assertFileExists('./tmp/upload/demo_thumb.png');
        @unlink('./tmp/upload/demo.png');
        @unlink('./tmp/upload/demo_thumb.png');
    }

    public function testDeleteOldFormat()
    {
        // On crée un faux format
        $property = (new \ReflectionClass($this->upload))->getProperty('formats');
        $property->setAccessible(true);
        $property->setValue($this->upload, ['thumb' => [100, 200]]);
        // On s'attend à obtenir une image miniature
        touch('./tmp/upload/demo.png');
        touch('./tmp/upload/demo_thumb.png');
        $this->upload->deleteFile('demo.png');
        $this->assertFileNotExists('./tmp/upload/demo.png');
        $this->assertFileNotExists('./tmp/upload/demo_thumb.png');
        @unlink('./tmp/upload/demo.png');
        @unlink('./tmp/upload/demo_thumb.png');
    }
}
