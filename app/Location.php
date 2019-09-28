<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Polygon;

class Location extends Model
{
    use PostgisTrait;

    protected $postgisFields = [
        'geom'
    ];

    
    protected $postgisTypes = [
        'geom' => [
            'geomtype' => 'geometry',
            'srid' => 4326
        ]
    ];

    protected $fillable = ['opis', 'user'];
}
