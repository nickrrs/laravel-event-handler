<?php

namespace App\Repository\Users;

use App\Models\Achievement;
use App\Models\User;

class UsersRepository implements UsersRepositoryInterface{
    
    public function storeUserLesson($eventPayload): User {
        $user = User::find($eventPayload->user->id);
        $user->watched()->attach($eventPayload->lesson->id, ['watched' => true]);

        return $user;
    }

    public function storeNewAchievement($eventPayload): User {
        
        $user = User::find($eventPayload->user->id);
        $user->achievements()->create([
            'user_id' => $user->id,
            'name' => $eventPayload->achievement
        ]);
    
        return $user;
    }

    public function storeNewBadge($eventPayload): User {
        $user = User::find($eventPayload->user->id);
        $user->badges()->create([
            'user_id' => $user->id,
            'name' => $eventPayload->badge
        ]);
    
        return $user;
    }
}