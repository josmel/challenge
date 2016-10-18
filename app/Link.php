<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model {

    protected $table = 'links';
    protected $fillable = ['url', 'hash', 'created', 'total'];

    public function setUrlAttribute($value) {
        $hash = str_random(6);

        if ($this->where('url', $value)->exists()) {
            return;
        }

        $this->attributes['url'] = $value;
        $this->attributes['hash'] = $hash;
    }

    public function getUrl($url) {
        return $this->where('url', $url)->first();
    }

    public function getHash($hash) {
        return $this->where('hash', $hash)->first();
    }

}
