<?php

namespace App\Enum;

enum SubjectType :string
{
    case NinthGrade = 'الصف التاسع';
    case ScientificBaccalaureate = 'البكالوريا العلمية';
    case LiteraryBaccalaureate = 'البكالوريا الأدبية';

    public static function getType(){
        return [
            self::LiteraryBaccalaureate,self::ScientificBaccalaureate,self::NinthGrade
        ];
    }
}
