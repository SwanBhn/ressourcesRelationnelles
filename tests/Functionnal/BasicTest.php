<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ressources');

        if ($crawler != null){
            $this->assertResponseIsSuccessful();
        }
        else{
            $this->assert(false);
        }
    }
}
