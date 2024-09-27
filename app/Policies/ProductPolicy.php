<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    
    public function create(User $user)
    {
        return $user->isAdmin(); 
    }
    
    public function update(User $user, Product $product)
    {
        return $user->isAdmin();
    }
}
