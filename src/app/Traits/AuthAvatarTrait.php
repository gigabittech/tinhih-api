<?php

namespace App\Traits;

trait AuthAvatarTrait
{

    private $url = 'https://ui-avatars.com/api/?background=random&name=Elon+Musk';
    public function avatar($name)
    {
        $formattedName = str_replace(' ', '+', $name);
        return "https://ui-avatars.com/api/?name=$formattedName";
    }
}
