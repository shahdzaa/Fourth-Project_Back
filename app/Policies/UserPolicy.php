<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // فقط من يملك توكن بصلاحية 'user:view' يمكنه عرض القائمة
        return $user->tokenCan('user:view');
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه إنشاء موديل.
     */
    public function create(User $user): bool
    {
        // فقط من يملك توكن بصلاحية 'user:create' يمكنه إنشاء مستخدم
        return $user->tokenCan('user:create');
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $currentUser, User $targetUser): bool
    {
        return $currentUser->tokenCan('user:view');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) {
            return false;
        }
        return $currentUser->tokenCan('user:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        // لا تسمح للأدمن بحذف نفسه
        if ($currentUser->id === $targetUser->id) {
            return false;
        }
        return $currentUser->tokenCan('user:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
