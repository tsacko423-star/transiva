<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * SimulateUser Middleware
 *
 * Pourquoi : Laravel n'accepte pas les Closures anonymes dans Route::middleware([...]).
 *            La logique de simulation de session (switch-user) est extraite ici
 *            sous forme de middleware nommé 'simulate.user'.
 *
 * Où utilisé : routes/web.php → groupe principal via ->middleware('simulate.user')
 *
 * Note variables (anglais) :
 *   - $userId  : identifiant de l'utilisateur simulé (était $utilisateurId)
 *   - $request : objet requête HTTP entrant
 *   - $next    : closure de passage au middleware suivant
 */
class SimulateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ID 4 correspond par défaut à Fatoumata Sidibé (Client/Traveler) dans transiva.sql
        // La session 'simulated_user_id' est définie par la route /switch-user/{id}
        $userId = session('simulated_user_id', 4);

        $user = User::find($userId);

        if ($user) {
            auth()->loginUsingId($userId);
        }

        return $next($request);
    }
}
