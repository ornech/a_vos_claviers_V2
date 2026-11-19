<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aux claviers citoyens !</title>
    <style>
        html, body { margin: 0; }
        body {
            font-family: system-ui, sans-serif;
            color: #1f2a44;
            background: #fff;
        }
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem 1rem 2.5rem;
        }
        img { max-width: 90vw; max-height: 45vh; height: auto; width: auto; }
        table {
            width: min(34rem, 100%);
            border-collapse: collapse;
        }
        th, td { padding: .5rem .75rem; border-bottom: 1px solid #dde2ee; text-align: left; }
        th { width: 40%; font-weight: 600; color: #4a5778; }
        .ok { color: #0a7a3d; font-weight: 600; }
        .ko { color: #b3261e; font-weight: 600; }
    </style>
</head>
<body>
    <main>
        <img src="{{ asset('images/aux-claviers-citoyens.png') }}"
             alt="Logo « Aux claviers citoyens ! » : une Marianne casquée, en armure, tient un drapeau aux chiffres binaires bleu, blanc, rouge"
             width="352" height="704">

        <table>
            @foreach ($infos as $nom => $valeur)
                <tr>
                    <th scope="row">{{ $nom }}</th>
                    <td>{{ $valeur }}</td>
                </tr>
            @endforeach
            <tr>
                <th scope="row">Base de données</th>
                <td>
                    @if ($base['ok'])
                        <span class="ok">Connectée</span> — {{ $base['driver'] }} {{ $base['version'] }}
                    @else
                        <span class="ko">Inaccessible</span>
                    @endif
                </td>
            </tr>
            @if ($base['ok'])
                <tr>
                    <th scope="row">Migrations appliquées</th>
                    <td>{{ $base['migrations'] }}</td>
                </tr>
            @endif
        </table>
    </main>
</body>
</html>
