<?php

namespace Avxman\Sitemap\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapModel extends Model
{

    protected $table = 'sitemap';
    protected $guarded = [];
    public $timestamps = false;

    public function scopeEnabled($query){
        return $query->where('enabled', 1);
    }

}
