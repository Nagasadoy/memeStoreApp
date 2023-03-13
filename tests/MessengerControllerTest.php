<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class MessengerControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $uploadedFile = new UploadedFile(
            __DIR__ . '/../fixtures/test_data.csv',
            'test_data.csv'
        );

        $client->request('POST', '/api/message/send', [],
            ['csv' => $uploadedFile]
        );
        $this->assertResponseIsSuccessful();

        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.async');

        $this->assertCount(1, $transport->get());
    }
}