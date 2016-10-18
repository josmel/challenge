<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use Lang;
use App\Link;

class LinksController extends Controller {

    protected $link;

    public function __construct() {
        $this->link = new Link();
    }

    public function index() {
        $links = Link::all();
        return view('welcome', compact('links'));
    }

    public function redirect($hash) {
        if (!$link = $this->link->getHash($hash)) {
            abort(404);
        }
        $link->update(['total'=>$link->total+1]);
        return redirect($link->url);
    }

    public function saveLink(SearchRequest $request) {
        if ($data = $this->link->getUrl($request->input('url'))) {
            return redirect()
                            ->back()
                            ->with('link', $data->hash)
                            ->with('success', Lang::get('msg.existe'));
        }

        $this->link->url = $request->get('url');
        $this->link->created = $_SERVER['REMOTE_ADDR'];
        $this->link->save();

        return redirect()
                        ->back()
                        ->with('link', $this->link->hash)
                        ->with('success', Lang::get('msg.ok'));
    }

}
