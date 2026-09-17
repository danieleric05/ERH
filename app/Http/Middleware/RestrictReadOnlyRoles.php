<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Politique d'accès (définie par Daniel le 2026-09-17) :
 * - idrole 2 (Admin: Directeur) : accès complet, aucune restriction.
 * - idrole 1 (Assistant) : accès complet sauf Gestion Utilisateurs (réservée au rôle 2).
 * - tout le reste (3=Chef d'équipe, 4=Chef d'unité, 5=Commerciaux, 6=Qualité, ou sans rôle) :
 *   lecture seule sur toute l'app.
 *
 * L'app ne suit pas la convention GET=lecture/POST=écriture de façon fiable : plusieurs
 * routes GET écrivent réellement en base, et deux routes POST sont de simples recherches.
 * Ces exceptions ont été auditées manuellement (routes/web.php) et sont listées ci-dessous ;
 * toute nouvelle route de ce type doit être ajoutée à la bonne liste ici.
 */
class RestrictReadOnlyRoles
{
    /** Routes nommées Gestion Utilisateurs, réservées au rôle 2 (Directeur) uniquement. */
    private const ADMIN_ONLY_ROUTE_NAMES = [
        'listeutilisateurs',
        'ajouterutilisateur',
        'post_utilisateur',
        'modifierutilisateur',
        'post_edit_utilisateur',
        'statututilisateur',
    ];
    private const ADMIN_ONLY_PATHS = ['settings'];

    /** Routes GET qui écrivent réellement en base (à traiter comme une écriture). */
    private const WRITE_GET_ROUTE_NAMES = [
        'statututilisateur',
        'detect_matricul',
        'validerConge',
        'refuserConge',
        'accident_travail_traiter',
        'sanctionvariable',
        'autorisationvariable',
        'missionvariable',
        'variables_sante',
        'patientrexu',
    ];
    private const WRITE_GET_PATHS = [
        'delete/departements/data',
        'delete/unites/data',
        'delete/equipes/data',
        'delete/fonctions/data',
        'delete/niveauEtude/data',
        'delete/categories/data',
        'delete/pays/data',
    ];

    /** Routes POST qui sont en réalité de simples lectures (recherche). */
    private const READ_ONLY_POST_PATHS = [
        'post_search',
        'post_search_varaiables',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        $idrole = $user->idrole;

        if ($idrole == 2) {
            return $next($request);
        }

        $isAdminRoute = ($request->route()?->getName() && in_array($request->route()->getName(), self::ADMIN_ONLY_ROUTE_NAMES))
            || $request->is(self::ADMIN_ONLY_PATHS);

        if ($idrole == 1) {
            if ($isAdminRoute) {
                abort(403, "Accès réservé aux administrateurs.");
            }
            return $next($request);
        }

        // Tout le reste : lecture seule.
        if ($isAdminRoute) {
            abort(403, "Accès réservé aux administrateurs.");
        }

        $routeName = $request->route()?->getName();
        $isWrite = !in_array($request->method(), ['GET', 'HEAD']);

        if ($request->method() === 'GET') {
            $isWrite = ($routeName && in_array($routeName, self::WRITE_GET_ROUTE_NAMES))
                || $request->is(self::WRITE_GET_PATHS);
        } elseif (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $isWrite = !$request->is(self::READ_ONLY_POST_PATHS);
        }

        if ($isWrite) {
            abort(403, "Accès en lecture seule. Contactez un administrateur pour effectuer cette action.");
        }

        return $next($request);
    }
}
