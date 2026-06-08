<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function promote(User $user)
    {
        $actor = Auth::user();
        abort_unless($actor && in_array((int) $actor->privilege_level, [2, 3], true), 403);

        if ((int) $user->privilege_level === 3) {
            return redirect()->back()->with('status', 'لا يمكن تعديل صلاحيات المالك.');
        }

        $user->privilege_level = 2;
        $user->save();

        return redirect()->back()->with('status', "تم ترقية {$user->name} إلى مشرف.");
    }

    public function demote(User $user)
    {
        $actor = Auth::user();
        abort_unless($actor && (int) $actor->privilege_level === 3, 403);

        if ((int) $user->privilege_level === 3) {
            return redirect()->back()->with('status', 'لا يمكن تعديل صلاحيات المالك.');
        }

        $user->privilege_level = 1;
        $user->save();

        return redirect()->back()->with('status', "تم إنزال {$user->name} إلى مستخدم.");
    }
}
