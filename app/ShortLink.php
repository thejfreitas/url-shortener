<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $fillable = [
        'code', 
        'url',
    ];

    public function format()
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'code' => $this->code,
            'hits' => $this->hits,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
