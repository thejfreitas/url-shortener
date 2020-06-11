<?php

namespace Tests\Feature;

use Tests\TestCase;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortenUrlTest extends TestCase
{
    use RefreshDatabase;

    public function testUserAccessTheIndexPage()
    {
        $response = $this->get(route('shortener.index'));

        $response
            ->assertStatus(RESPONSE::HTTP_OK)
            ->assertSeeInOrder(['Url Shortener', 'Insert your url', 'Shorten']);
    }

    public function testUserStoreUrlAndRedirectsToHome()
    {
        $response = $this->post(route('shortener.store.link'), [
            'url' => 'https://jfreitas.dev',
            'code' => 'xyz',
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('shortener.index'));

        $shortLink = route('shortener.index') . '/xyz';

        $responseHome = $this->get(route('shortener.index'));
        $responseHome->assertSeeInOrder(['https://jfreitas.dev', $shortLink]);
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
        $url = 'https://jfreitas.dev';

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
        $url = 'https://jfreitas.dev';
        $code = 'XYZPTO';

        $this->post(route('shortener.store.link'), [
            'url' => $url,
            'code' => $code,
        ]);

        $redirectUrl = route('shortener.get.link', ['code' => $code]);

        $response = $this->get($redirectUrl);

        $response->assertRedirect($url);
    }

    public function testHitsMustIncreaseByOneForEachRedirect()
    {
        $url = 'https://jfreitas.dev';
        $code = 'XYZPTO';
        $redirectionTimes = 3;

        $this->post(route('shortener.store.link'), [
            'url' => $url,
            'code' => $code,
        ]);

        for ($x = 0; $x < $redirectionTimes; $x++) {
            $response = $this->get(
                route('shortener.get.link', ['code' => $code])
            );
        }

        $shortLink = route('shortener.index') . '/' . $code;

        $responseHome = $this->get(route('shortener.index'));
        $responseHome
            ->assertSeeInOrder([$url, $shortLink])
            ->assertSeeText($redirectionTimes);
    }
}
