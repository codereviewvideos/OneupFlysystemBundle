<?php


namespace Oneup\FlysystemBundle\Tests\StreamWrapper;


use League\Flysystem\FilesystemInterface;
use Oneup\FlysystemBundle\Tests\Model\ContainerAwareTestCase;

class StreamWrapperTest extends ContainerAwareTestCase
{
    public function testStreamWrapperForMyFilesystem()
    {
        $path = 'stream-wrapper-test';
        $uri = 'myfilesystem://'.$path;
        $content = 'myfilesystem-stream-wrapper-test';

        $this->assertNotFalse(file_put_contents($uri, $content), 'Can write content via stream wrapper');
        $this->assertEquals($content, file_get_contents($uri), 'Can read content via stream wrapper');

        /* @var FilesystemInterface $filesystem */
        $filesystem = $this->client->getContainer()->get('oneup_flysystem.myfilesystem_filesystem');

        $this->assertTrue($filesystem->has($path));
        $this->assertEquals($content, stream_get_contents($filesystem->readStream($path)));
    }

    public function testStreamWrapperUnregistration()
    {
        $this->assertContains('myfilesystem', stream_get_wrappers());
        $this->ensureKernelShutdown();
        $this->assertNotContains('myfilesystem', stream_get_wrappers());
    }
}
