<?php 

namespace App\Services\Users;

use App\Models\User;
use App\Repository\Users\UsersRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class UsersService {

    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function storeUserLesson($eventPayload) 
    {
        try{
            $userLesson = $this->usersRepository->storeUserLesson($eventPayload);
            return $userLesson;
        } catch (Exception $e) {
            Log::alert("Error while trying to save a new lesson for the user {$eventPayload->user->id}: {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    public function storeNewAchievement($eventPayload)
    {
        try{
            $userAchievement = $this->usersRepository->storeNewAchievement($eventPayload);
            return $userAchievement;
        } catch (Exception $e) {
            Log::alert("Error while trying to save a new achievement for the user {$eventPayload->user->id}: {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    public function storeNewBadge($eventPayload)
    {
        try{
            $userBadge = $this->usersRepository->storeNewBadge($eventPayload);
            return $userBadge;
        } catch (Exception $e) {
            Log::alert("Error while trying to save a new badge for the user {$eventPayload->user->id}: {$e->getMessage()}");
            return $e->getMessage();
        }
    }
}