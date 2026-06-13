<?php

namespace App\Enum;

enum UserRole: string
{
    case Artist = 'artist';
    case Customer = 'customer';
}
