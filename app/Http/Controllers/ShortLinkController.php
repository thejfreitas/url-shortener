<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ShortLinkRepository;


class ShortLinkController extends Controller
{
    private $shortLinkRespository;

    public function __construct(ShortLinkRepository $shortLinkRespository) 
    {
        $this->shortLinkRespository = $shortLinkRespository;
    }

    /**
     * Display all short links
     * 
     * @return view
     */
    public function index() 
    {
        $links = $this->shortLinkRespository->all();

        return view('index', compact('links'));
    }

    /**
     * Store a given url
     * 
     * @param Request $request
     * 
     * @return redirect
     */
    public function store(Request $request) 
    {
        if ($this->shortLinkRespository->store($request)) {
            return redirect()->route('shortener.index')->with('success', 'Your url has been shortened.');    
        }
    }

    /**
     * Get a given link code and redirects to the stored url
     * 
     * @param $linkCode
     * 
     * @return redirect
     */
    public function redirectShortLink($linkCode) 
    {    
        if ($link = $this->shortLinkRespository->findAndUpdateShortLink($linkCode)) {
            return redirect($link->url);
        }

        return redirect()->route('shortener.index')->withErrors(['This short url does not exist.']);
    }
}
