<?php

namespace App\Repository\Users;

use App\Models\User;

interface UsersRepositoryInterface {
    public function storeUserLesson($eventPayload);
    public function storeNewAchievement($eventPayload);
    public function storeNewBadge($eventPayload);
}
