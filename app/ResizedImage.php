<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResizedImage extends Model
{

    protected $table = "resized_images";
    //
    protected $fillable = [
        'image_url', 'max_width', 'max_height', 'image_content', 'image_type', 'random_generated_path'
    ];

    protected $hidden = [
        /*'image_content',*/ '	user_id'
    ];
}
