<?php

namespace App\Infrastructure;

use App\Models\Email;
use Ecotone\Messaging\Attribute\Converter;

class EmailConverter
{
    #[Converter]
    public function from(string $email): Email
    {
        return new Email($email);
    }
}
