<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'password',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
        'fcm_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class);

    }

public function teacher()
{
    return $this->hasOne(Teacher::class);
}

public function parentModel()
{
    return $this->hasOne(ParentModel::class); // because we used ParentModel
}

public function manager()
{
    return $this->hasOne(Manager::class);
}
public function verifyCode()
{
    return $this->hasOne(VerifyCode::class);
}

public function scopeForQuiz($query, $quizId)
{
    return $query->whereHas('student.courses', function ($q) use ($quizId) {
        $q->whereHas('curriculums.quizzes', function ($q2) use ($quizId) {
            $q2->where('quizzes.id', $quizId);
        });
    });
}

public function getUserDataAttribute()
{
    if ($this->student) {
         // return array_merge(
        //     $this->only(['id','password']),
        //     $this->student->only(['id', 'user_id']),
        //     ['role' => 'student']
        // );
        return [
            'id' => $this->id,
            'role_data' => $this->student->only([
                'id', 'user_id','student_Id_number','first_name','last_name','number_civial','address','mother_name','father_name',
                // 'QR','location',
                'access_code','created_at','updated_at',
            ]),
            'role' => 'student',
        ];
    } elseif ($this->teacher) {
        return [
        'id' => $this->id,
        'role_data' => $this->teacher->only([
            'id',
            'user_id',
            'name',
            'email',
            'date_of_contract',
            // 'avatar',
            'bio',
            // 'created_at',
            // 'updated_at',
        ]),
        'role' => 'teacher',
    ];
    }elseif ($this->parentModel) {
        return [
        'id' => $this->id,
        'role_data' => $this->parentModel->only([
            'id',
            'user_id',
            'name',
            'phone_number',
            // 'created_at',
            // 'updated_at',
        ]),
        'role' => 'parent',
    ];
    }elseif ($this->manager) {
        return [
        'id' => $this->id,
        'role_data' => $this->manager->only([
            'id',
            'user_id',
            'email',
            // 'created_at',
            // 'updated_at',
        ]),
        'role' => 'manager',
    ];

    }
}

// public function getRoleDataAttribute()
// {
//     if ($this->student) {
//         return [
//             'role' => 'student',
//             ...$this->only(['id', 'name', 'email']),
//             ...$this->student->only(['id', 'user_id', 'birthdate', 'student_number']),
//         ];
//     } elseif ($this->teacher) {
//         return [
//             'role' => 'teacher',
//             ...$this->only(['id', 'name', 'email']),
//             ...$this->teacher->only(['id', 'user_id', 'department', 'employee_number']),
//         ];
//     } elseif ($this->parentModel) {
//         return [
//             'role' => 'parent',
//             ...$this->only(['id', 'name', 'email']),
//             ...$this->parentModel->only(['id', 'user_id', 'address', 'phone_number']),
//         ];
//     } elseif ($this->manager) {
//         return [
//             'role' => 'manager',
//             ...$this->only(['id', 'name', 'email']),
//             ...$this->manager->only(['id', 'user_id', 'position']),
//         ];
//     }

//     return null;
// }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


}
