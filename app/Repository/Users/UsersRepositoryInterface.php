<?php

namespace App\Repository\Users;

interface UsersRepositoryInterface 
{
    public function storeUserLesson($eventPayload);
    public function storeNewAchievement($eventPayload);
    public function storeNewBadge($eventPayload);
}
