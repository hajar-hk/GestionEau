<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>RG8 - {{ $paiement->numero_rg8 }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3,
        .header p {
            margin: 0;
        }

        .info-box {
            border: 1px solid black;
            padding: 10px;
            margin-top: 15px;
        }

        .info-box table {
            width: 100%;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .details-table th {
            font-weight: bold;
            background-color: #eee;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
        }

        .signatures .left {
            float: left;
            width: 45%;
            text-align: center;
        }

        .signatures .right {
            float: right;
            width: 45%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>OFFICE REGIONAL DE MISE EN VALEUR AGRICOLE DE LA MOULOUYA</h3>
        <p>BERKANE</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td><strong>Régie N°:</strong> {{-- Numéro de régie --}}</td>
                <td><strong>RG8 N°:</strong> {{ $paiement->numero_rg8 }}</td>
            </tr>
            <tr>
                <td><strong>Reçu de:</strong> {{ $paiement->facture->client->nom_client }}
                    {{ $paiement->facture->client->prenom_client }}</td>
                <td><strong>Code Client:</strong> {{ $paiement->facture->client->code_client }}</td>
            </tr>
            <tr>
                <td><strong>Somme de:</strong> {{-- Somme en lettres --}} DHS</td>
                <td><strong>Mode de paiement:</strong> {{ $paiement->methode_reglement }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Objet du règlement:</strong> REDEVANCE D'EAU D'IRRIGATION</td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <thead>
            <tr>
                <th rowspan="2">ARTICLES</th>
                <th colspan="3">ORMVAM</th>
                <th rowspan="2">T/PÉNALITÉ</th>
                <th rowspan="2">TOTAL GÉNÉRAL</th>
            </tr>
            <tr>
                <th>MT</th>
                <th>LR</th>
                <th>F.C</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $paiement->facture->numero_facture }}</td>
                <td>{{ number_format($paiement->montant_paye, 2) }}</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>{{ number_format($paiement->penalite_retard, 2) }}</td>
                <td>{{ number_format($paiement->montant_paye + $paiement->penalite_retard, 2) }}</td>
            </tr>
            <tr>
                <td><strong>TOTAUX</strong></td>
                <td><strong>{{ number_format($paiement->montant_paye, 2) }}</strong></td>
                <td><strong>0.00</strong></td>
                <td><strong>0.00</strong></td>
                <td><strong>{{ number_format($paiement->penalite_retard, 2) }}</strong></td>
                <td><strong>{{ number_format($paiement->montant_paye + $paiement->penalite_retard, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="signatures">
        <div class="left">
            <p><strong>A BERKANE le:</strong> {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
            </p>
        </div>
        <div class="right">
            <p><strong>LE RÉGISSEUR</strong></p>
            <br><br>
            <p>Signé: {{ $paiement->user->prenom }} {{ $paiement->user->nom }}</p>
        </div>
    </div>
</body>

</html>
