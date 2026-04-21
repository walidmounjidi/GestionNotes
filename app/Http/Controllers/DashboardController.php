<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->isManager()) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->isTeacher()) {
            return redirect()->route('dashboard.teacher');
        }

        if ($user->isStudent()) {
            return redirect()->route('dashboard.student');
        }

        abort(403, 'Aucun rôle valide trouvé. Veuillez contacter l\'administrateur.');
    }
}
