<?php 
namespace App\Enums;

enum CommentsAchievementEnum:string {
    case FirtComment = 'First Comment Written';
    case ThreeComments = '3 Comments Written';
    case FiveComments = '5 Comments Written';
    case TenComments = '10 Comments Written';
    case TwentyComments = '20 Comments Written';
}