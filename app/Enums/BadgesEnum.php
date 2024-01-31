<?php 
namespace App\Enums;

enum BadgesEnum:string {
    case Beginner = 'Beginner';
    case Intermediate = 'Intermediate';
    case Advanced = 'Advanced';
    case Master = 'Master';
}