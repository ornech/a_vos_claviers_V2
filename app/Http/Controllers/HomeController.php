<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use PDO;
use Throwable;

class HomeController extends Controller
{
    /**
     * Page d'accueil : état de l'environnement avant de commencer à coder.
     */
    public function __invoke(): View
    {
        return view('home', [
            'infos' => [
                'Laravel' => app()->version(),
                'PHP' => PHP_VERSION,
                'Environnement' => app()->environment(),
                'Conteneur' => gethostname(),
                'Heure du serveur' => now()->format('d/m/Y H:i:s').' ('.config('app.timezone').')',
            ],
            'base' => $this->etatBase(),
        ]);
    }

    /**
     * Tente de joindre la base de données sans jamais faire échouer la page.
     *
     * @return array{ok: bool, driver?: string, version?: string, migrations?: int}
     */
    private function etatBase(): array
    {
        try {
            $connexion = DB::connection();

            return [
                'ok' => true,
                'driver' => $connexion->getDriverName(),
                'version' => $connexion->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION),
                'migrations' => Schema::hasTable('migrations') ? DB::table('migrations')->count() : 0,
            ];
        } catch (Throwable) {
            return ['ok' => false];
        }
    }
}
