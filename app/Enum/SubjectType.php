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
    public static function getTypeId($id){
        //switch case
        if($id==1)
            return self::NinthGrade;
        elseif($id==2)
            return self::ScientificBaccalaureate;
        elseif($id==3)
            return self::LiteraryBaccalaureate;
    }


}
