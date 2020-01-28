<?php

namespace App\Service;

class Slugger
{
    public function slugify(?string $str): string
    {
        // str est la chaine de caractère à slugifier
        return preg_replace('/[^a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*/', '-', strtolower(trim(strip_tags($str))));
    }
}