<?php

namespace Tests\Feature;

use Tests\TestCase;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortenUrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserStoreUrlAndRedirectsToHome()
    {
        $response = $this->post(route('shortener.store.link'), [
            'url' => 'https://jfreitas.net',
            'code' => 'xyz',
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('shortener.index'));
    }

    public function testUrlIsRequired()
    {
        $response = $this->post(route('shortener.store.link'), [
            'url' => null,
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function testUrlMustBeValid()
    {
        $response = $this->post(route('shortener.store.link'), [
            'url' => 'no-url',
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function testUrlMustBeUnique()
    {
        $url = 'https://jfreitas.net';

        $this->post(route('shortener.store.link'), [
            'url' => $url,
        ]);

        $response = $this->post(route('shortener.store.link'), [
            'url' => $url,
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function testShortUrlMustRedirect()
    {
        $url = 'https://jfreitas.net';
        $code = 'XYZPTO';

        $this->post(route('shortener.store.link'), [
            'url' => $url,
            'code' => $code,
        ]);

        $redirectUrl = route('shortener.get.link', ['code' => $code]);

        $response = $this->get($redirectUrl);

        $response->assertRedirect($url);
    }
}
