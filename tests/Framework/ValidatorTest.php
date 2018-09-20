<?php
namespace Tests\Framework;

use Framework\Validator;
use GuzzleHttp\Psr7\UploadedFile;
use Tests\DatabaseTestCase;

class ValidatorTest extends DatabaseTestCase
{

    private function makeValidator(array $params)
    {
        return new Validator($params);
    }

    public function testRequiredIfFailed()
    {
        $errors = $this->makeValidator(['name' => 'Joe'])
            ->required('name', 'content')
            ->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testNotEmpty()
    {
        $errors = $this->makeValidator(['name' => 'Joe', 'content' => ''])
            ->notEmpty('content')
            ->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testRequiredIfSuccess()
    {
        $errors = $this->makeValidator(['name' => 'Joe', 'content' => 'azeaze'])
            ->required('name', 'content')
            ->getErrors();
        $this->assertCount(0, $errors);
    }

    public function testSlugSuccess()
    {
        $errors = $this->makeValidator([
            'slug' => 'aze-aze-1',
            'slug2' => 'azeaze',
        ])
            ->isSlug('slug')
            ->isSlug('slug2')
            ->getErrors();
        $this->assertCount(0, $errors);
    }

    public function testSlugError()
    {
        $errors = $this->makeValidator([
            'slug' => 'aze-Aze-1',
            'slug2' => 'aze_aze-1',
            'slug3' => 'aze--aze-1',
            'slug4' => 'aze-aze-'
        ])
            ->isSlug('slug')
            ->isSlug('slug2')
            ->isSlug('slug3')
            ->isSlug('slug4')
            ->getErrors();
        $this->assertEquals(['slug','slug2','slug3','slug4'], array_keys($errors));
    }

    public function testTextLength()
    {
        $params = [
            'slug' => '123456789'
        ];
        $this->assertCount(0, $this->makeValidator($params)->textLength('slug', 3)->getErrors());
        $errors = $this->makeValidator($params)->textLength('slug', 12)->getErrors();
        $this->assertCount(1, $errors);
        $this->assertCount(1, $this->makeValidator($params)->textLength('slug', 3, 4)->getErrors());
        $this->assertCount(0, $this->makeValidator($params)->textLength('slug', null, 20)->getErrors());
        $this->assertCount(1, $this->makeValidator($params)->textLength('slug', null, 8)->getErrors());
    }

    public function testDateTime()
    {
        $params = ['date' => '2012-12-12 11:12:13'];
        $this->assertCount(0, $this->makeValidator($params)->isDateTime('date')->getErrors());
        $this->assertCount(0, $this->makeValidator(['date' => '2012-12-12 00:00:00'])->isDateTime('date')->getErrors());
        $this->assertCount(1, $this->makeValidator(['date' => '2012-21-12'])->isDateTime('date')->getErrors());
        $this->assertCount(1, $this->makeValidator(['date' => '2013-02-29 11:12:13'])->isDateTime('date')->getErrors());
    }

    public function testExist()
    {
        $pdo = $this->getPdo();
        $pdo->exec('CREATE TABLE test (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          name VARCHAR(255)
        )');
        $pdo->exec('INSERT INTO test (name) VALUES ("a1")');
        $pdo->exec('INSERT INTO test (name) VALUES ("a2")');
            $this->assertTrue($this->makeValidator(['catagory' => 1])->exists('catagory', 'test', $pdo)->isValid());
        $this->assertFalse($this->makeValidator(['catagory' => 11213])->exists('catagory', 'test', $pdo)->isValid());
    }

    public function testUniq()
    {
        $pdo = $this->getPdo();
        $pdo->exec('CREATE TABLE test (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          name VARCHAR(255)
        )');
        $pdo->exec('INSERT INTO test (name) VALUES ("a1")');
        $pdo->exec('INSERT INTO test (name) VALUES ("a2")');
        $this->assertFalse($this->makeValidator(['name' => 'a1'])->isUnique('name', 'test', $pdo)->isValid());
        $this->assertTrue($this->makeValidator(['name' => 'a111'])->isUnique('name', 'test', $pdo)->isValid());
        $this->assertTrue($this->makeValidator(['name' => 'a1'])->isUnique('name', 'test', $pdo, 1)->isValid());
        $this->assertFalse($this->makeValidator(['name' => 'a2'])->isUnique('name', 'test', $pdo, 1)->isValid());
    }

    public function testUploadedFile()
    {
        $file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->setMethods(['getError'])
            ->getMock();
        $file->expects($this->once())->method('getError')->willReturn(UPLOAD_ERR_OK);
        $file2 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->setMethods(['getError'])
            ->getMock();
        $file2->expects($this->once())->method('getError')->willReturn(UPLOAD_ERR_CANT_WRITE);
        $this->assertTrue($this->makeValidator(['image' => $file])->uploaded('image')->isValid());
        $this->assertFalse($this->makeValidator(['image' => $file2])->uploaded('image')->isValid());
    }

    public function testExtension()
    {
        $file = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $file->expects($this->any())->method('getError')->willReturn(UPLOAD_ERR_OK);
        $file->expects($this->any())->method('getClientFileName')->willReturn('demo.jpg');
        $file->expects($this->any())
            ->method('getClientMediaType')
            ->will($this->onConsecutiveCalls('image/jpeg', 'fake/php'));
        $this->assertTrue($this->makeValidator(['image' => $file])->extension('image', ['jpg'])->isValid());
        $this->assertFalse($this->makeValidator(['image' => $file])->extension('image', ['jpg'])->isValid());
    }

    public function testIsEmail()
    {
        $this->assertTrue($this->makeValidator(['email' => 'demo@local.dev'])->email('email')->isValid());
        $this->assertFalse($this->makeValidator(['email' => 'aazeaze'])->email('email')->isValid());
    }

    public function testConfirm()
    {
        $this->assertFalse($this->makeValidator(['slug' => 'aze'])->confirm('slug')->isValid());
        $this->assertFalse(
            $this->makeValidator(['slug' => 'aze', 'slug_confirm' => 'azeaze'])->confirm('slug')->isValid()
        );
        $this->assertTrue($this->makeValidator(['slug' => 'aze', 'slug_confirm' => 'aze'])->confirm('slug')->isValid());
    }
}
