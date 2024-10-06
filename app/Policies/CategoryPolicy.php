<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }
    function update(User $user): bool 
    {
        return $user->isAdministrator();
    }

    function delete(User $user, Category $category): bool 
    {
        // to make sure there is products_count.
        $category->loadCount('products');
        return $this->update($user) && ($category->products_count === 0);
    }
}
