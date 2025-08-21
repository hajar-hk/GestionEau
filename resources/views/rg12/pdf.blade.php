<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bordereau RG12 - {{ $bordereau->numero_rg12 }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h3 {
            margin: 0;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }

        .details-table th {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .signatures {
            margin-top: 40px;
            width: 100%;
        }

        .signatures .left,
        .signatures .right {
            display: inline-block;
            width: 48%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>OFFICE REGIONAL DE MISE EN VALEUR AGRICOLE DE LA MOULOUYA</h3>
        <h3>Bordereau des Recettes</h3>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>CMV:</strong> __________</td>
            <td><strong>RG12:</strong> {{ $bordereau->numero_rg12 }}</td>
        </tr>
        <tr>
            <td><strong>Régie:</strong> __________</td>
            <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($bordereau->date_creation)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th rowspan="2">Code</th>
                <th rowspan="2">Nom & Prénom</th>
                <th rowspan="2">RG8</th>
                <th colspan="4">ORMVAM</th>
                <th colspan="4">ABHM</th>
                <th colspan="2">D. TIMBRE</th>
                <th rowspan="2">TOTAL GÉNÉRAL</th>
            </tr>
            <tr>
                <th>Principale</th>
                <th>L.R</th>
                <th>F.C</th>
                <th>TOTAL</th>
                <th>Principale</th>
                <th>L.R</th>
                <th>F.C</th>
                <th>TOTAL</th>
                <th>Principale</th>
                <th>L.R</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bordereau->paiements as $paiement)
                <tr>
                    <td>{{ $paiement->facture->client->code_client }}</td>
                    <td>{{ $paiement->facture->client->prenom_client }} {{ $paiement->facture->client->nom_client }}
                    </td>
                    <td>{{ $paiement->numero_rg8 }}</td>
                    <td>{{ number_format($paiement->montant_paye, 2) }}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>{{ number_format($paiement->montant_paye, 2) }}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>{{ number_format($paiement->montant_paye + $paiement->penalite_retard, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>TOTAUX</strong></td>
                <td><strong>{{ number_format($bordereau->paiements->sum('montant_paye'), 2) }}</strong></td>
                <td><strong>0</strong></td>
                <td><strong>0</strong></td>
                <td><strong>{{ number_format($bordereau->paiements->sum('montant_paye'), 2) }}</strong></td>
                <td><strong>0</strong></td>
                <td><strong>0</strong></td>
                <td><strong>0</strong></td>
                <td><strong>0</strong></td>
                <td><strong>0</strong></td>
                <td><strong>0</strong></td>
                <td><strong>{{ number_format($bordereau->montant_total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="left">
            <p><strong>L'ORDONNATEUR</strong></p>
        </div>
        <div class="right">
            <p><strong>LE RÉGISSEUR</strong></p>
        </div>
    </div>
</body>

</html>
