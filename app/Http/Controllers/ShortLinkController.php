<?php

namespace App\Http\Controllers;

use App\ShortLink;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ShortLinkController extends Controller
{
    /**
     * Display all short links
     */
    public function index() {
        $links = ShortLink::latest()->get();

        return view('index', compact('links'));
    }

    /**
     * Store a given link in the database
     * 
     * @param $request
     * 
     */
    public function store(Request $request) 
    {
        $linkCode = Str::random(6);

        $request->request->add(['code' => $linkCode]);
        
        $data = $request->validate([
            'url' => 'required|url|unique:short_links,url',
            'code' => 'string|unique:short_links,code'
        ]);

        ShortLink::create($data);
        
        return redirect()->route('shortener.index')->with('success', 'Your url has been shortened.');
    }

    
    public function redirectShortLink($linkCode) {
        
        $link = ShortLink::where('code', $linkCode)->first();
        
        if ($link) {
            $link->update(['hits' => $link->hits++]);

            return redirect($link->url);
        }

        return redirect()->route('shortener.index')->withErrors(['This short url does not exist.']);
    }
}
