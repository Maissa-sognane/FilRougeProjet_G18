<?php
// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostPromoControllerTest extends WebTestCase
{
    public function testShowPost()
    {
        $client = static::createClient();

        $client->request('POST', 'api/admin/promo');

       // $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

