<?php

namespace App\Policies;

use App\Models\Plate;
use App\Models\User;

class PlatePolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Plate $plate): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Plate $plate): bool { return $user->isAdmin(); }
    public function delete(User $user, Plate $plate): bool { return $user->isAdmin(); }
}
