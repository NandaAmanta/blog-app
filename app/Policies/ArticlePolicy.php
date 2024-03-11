<?php

namespace App\Policies;

use App\Const\Action;
use App\Const\Module;
use App\Const\RoleConst;
use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Action::READ . '.' . Module::ARTICLE);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(Action::READ . '.' . Module::ARTICLE)
            && ($article->creator_id == $user->id || $user->hasRole(RoleConst::ADMIN));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Action::CREATE . '.' . Module::ARTICLE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(Action::UPDATE . '.' . Module::ARTICLE)
            && ($article->creator_id == $user->id || $user->hasRole(RoleConst::ADMIN));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(Action::DELETE . '.' . Module::ARTICLE)
            && ($article->creator_id == $user->id || $user->hasRole(RoleConst::ADMIN));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(Action::UPDATE . '.' . Module::ARTICLE)
            && ($article->creator_id == $user->id || $user->hasRole(RoleConst::ADMIN));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(Action::DELETE . '.' . Module::ARTICLE)
            && ($article->creator_id == $user->id || $user->hasRole(RoleConst::ADMIN));
    }
}
