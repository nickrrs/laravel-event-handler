<?php 
namespace App\Enums;

enum AchievementsEnum:string {
    case FirtLesson = 'First Lesson Watched';
    case FiveLessons = '5 Lessons Watched';
    case TenLessons = '10 Lessons Watched';
    case TwentyFiveLessons = '25 Lessons Watched';
    case FifthLessons = '50 Lessons Watched';
}