<?php

namespace App\Interfaces;

use App\Models\Address;

interface UserInterface
{
    public function isAdmin(): bool;
    public function getDefaultAddressAttribute(): ?Address;
}
