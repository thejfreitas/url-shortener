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
            'hits' => $this->code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
