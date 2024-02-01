<?php 

namespace App\Services\Users;

use App\Models\Comment;
use App\Models\User;
use App\Repository\Users\UsersRepository;

class UsersService {

    private $usersRepository;

    public function __construct(UsersRepository $usersRepository){
        $this->usersRepository = $usersRepository;
    }

    public function storeUserLesson($eventPayload): User{
        $userLesson = $this->usersRepository->storeUserLesson($eventPayload);
        return $userLesson;
    }

    public function storeNewAchievement($eventPayload){
        $userAchievement = $this->usersRepository->storeNewAchievement($eventPayload);
        return $userAchievement;
    }

    public function storeNewBadge($eventPayload){
        $userBadge = $this->usersRepository->storeNewBadge($eventPayload);
        return $userBadge;
    }
}