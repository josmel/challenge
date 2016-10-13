<?php

namespace App\Http\Controllers;

use App\Link;
use Config;
use App\Http\Requests\SearchRequest;

class HomeController extends Controller {

    protected $link;
    protected $allowed;

    /**
     * LinksController constructor.
     */
    public function __construct() {
        $this->link = new Link();
        $this->allowed = Config::get('app.ALLOWED_CHARS');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $link = Link::all();

        foreach ($link as $value) {
//            $value->urlcorta = COnfig::get('app.BASE_HREF') . $this->getShortenedURLFromID($value->id);
        }


        return view('home', compact($link));
    }

    public function postSearch(SearchRequest $request) {

        $url_to_shorten = get_magic_quotes_gpc() ?
                stripslashes(trim($request->input('url'))) :
                trim($request->input('url'));

        if (!empty($url_to_shorten) && preg_match('|^https?://|', $url_to_shorten)) {


            // VALIDAR URL
            if (true) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url_to_shorten);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($ch);
                $response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($response_status == '404') {
                    return redirect()
                                    ->back()
                                    ->with('success', 'Url no encontrada error: 404');
                }
            }



            if ($data = $this->link->getUrl($url_to_shorten)) {
                $shortened_url = $this->getShortenedURLFromID($data->id);
                $data->update(['referrals' => $data->referrals + 1]);
            } else {

                //SI NO REGISTRAR URL
                $data = Link::create(["long_url" => $url_to_shorten,
                            "created" => time(),
                            "creator" => $_SERVER['REMOTE_ADDR'],
                            "mobile" => $this->isMobile() ? 1 : 0,
                ]);

                $shortened_url = $this->getShortenedURLFromID($data->id);
            }
        }
        return redirect()
                        ->back()
                        ->with('link', $shortened_url)
                        ->with('success', 'URL has been shortened.');
    }

    function getShortenedURLFromID($integer) {
        $length = strlen($this->allowed);
        dd($integer);
        while ($integer > $length - 1) {echo 4;exit;
            $out = $this->allowed[fmod($integer, $length)] . $out;
            $integer = floor($integer / $length);
        } echo 5;exit;
        dd();
        return $this->allowed[$integer] . $out;
    }

    function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

}
