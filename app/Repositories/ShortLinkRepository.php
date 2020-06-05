<?php

namespace App\Repositories;

use App\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ShortLinkRepository
{
    /**
     * Get all short links
     *
     * @return App\ShortLink
     */
    public function all()
    {
        return ShortLink::latest()->get();
    }

    /**
     * Create a new short link
     *
     * @param Request $request
     *
     * @return App\ShortLink
     */
    public function store(Request $request)
    {
        if (!$request->filled('code')) {
            $request->request->add([
                'code' => $this->generateLinkCode(),
            ]);
        }

        $data = $request->validate([
            'url' => 'required|url|unique:short_links,url',
            'code' => 'string|unique:short_links,code',
        ]);

        return ShortLink::create($data);
    }

    /**
     * Generates a random string based on the given size
     *
     * @param integer $size
     *
     * @return string
     */
    public function generateLinkCode($size = 6)
    {
        return Str::random($size);
    }

    /**
     * Find short link by the given link code
     *
     * @param $linkCode string
     *
     * @return App\ShortLink | null
     */
    public function findByCode($linkCode)
    {
        return ShortLink::where('code', $linkCode)->first();
    }

    /**
     * Increments link hits by one
     *
     * @param App\ShortLink
     *
     * @return ShortLink | boolean
     */
    public function updateHits(ShortLink $shortLink)
    {
        return $shortLink->update(['hits' => $shortLink->hits++]);
    }

    /**
     * Get a short link based on the given link code and update the number of hits by one.
     *
     * @param $linkCode string
     *
     * @return App\ShortLink | boolean
     */
    public function findAndUpdateShortLink($linkCode)
    {
        $link = $this->findByCode($linkCode);

        if ($link) {
            $this->updateHits($link);

            return $link;
        }

        return false;
    }
}
