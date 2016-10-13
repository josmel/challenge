<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created', 'long_url', 'creator', 'referrals'
    ];

    /**
     * Set the Url and Hash field values
     *
     * @param $value
     */
    public function setUrlAttribute($value) {
        $hash = str_random(6);

        // Check if url already exists
        if ($this->where('long_url', $value)->exists()) {
            return;
        }

        // Else insert the records into the table
        $this->attributes['long_url'] = $value;
        $this->attributes['hash'] = $hash;
    }

    /**
     * Get the url
     *
     * @param $url
     * @return mixed
     */
    public function getUrl($url) {
        return $this->where('long_url', $url)->first();
    }

    /**
     * Get the url hash
     *
     * @param $hash
     * @return mixed
     */
    public function getHash($hash) {
        return $this->where('hash', $hash)->first();
    }

}
