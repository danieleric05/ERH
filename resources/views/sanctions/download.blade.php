<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Sanction du travailleur - {{ $travailleur->nom }}</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.tailwindcss.com">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            padding: 20px;
        }
        h1 {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            color: #545b62;
            margin-bottom: 20px;
        }
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .header-section img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .employee-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #0066cc;
        }
        .sanction-section {
            margin: 30px 0;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            color: #000;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #333;
        }
        .sanction-option {
            margin: 10px 0;
            padding-left: 20px;
        }
        .checkbox {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            margin-right: 10px;
            font-weight: bold;
            text-align: center;
            line-height: 18px;
        }
        .motif-section {
            margin: 30px 0;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        .signature-section {
            margin-top: 50px;
            text-align: center;
        }
        .signature-block {
            display: inline-block;
            width: 200px;
            text-align: center;
            margin: 0 30px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 12px;
        }
        .date-info {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Header with Logo -->
        <div class="header-section">
            <img src="{{ asset('rhassets/images/CaptureSanction.PNG') }}" alt="Logo">
        </div>

        <!-- Date -->
        <div class="date-info">
            Date : {{ date('d') }} / {{ date('m') }} / {{ date('Y') }}
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div style="margin-bottom: 10px;"><strong>À</strong></div>
            <div style="margin: 8px 0;">
                <strong>Nom & Prénoms du travailleur :</strong> {{ strtoupper($travailleur->nom) }} {{ strtoupper($travailleur->prenom) }}
            </div>
            <div style="margin: 8px 0;">
                <strong>Unité :</strong> {{ $unites->label ?? '-' }}
            </div>
            <div style="margin: 8px 0;">
                <strong>Matricule :</strong> {{ $travailleur->matricule }}
            </div>
        </div>

        <!-- Sanction Section -->
        <div class="sanction-section">
            <div class="section-title">SANCTION PRISE</div>

            <div class="sanction-option">
                <span class="checkbox">@if($sanction->sanction_applique == 1)X@endif</span>
                Avertissement
            </div>

            <div class="sanction-option">
                <span class="checkbox">@if($sanction->sanction_applique == 2)X@endif</span>
                Mise à pieds
                @if($sanction->sanction_applique == 2)
                    <span style="margin-left: 20px;">
                        <strong>Durée :</strong> {{ $sanction->nombre_jour }} jour(s)
                    </span>
                    <span style="margin-left: 20px;">
                        Période : du .......... au ...........
                    </span>
                @endif
            </div>

            <div class="sanction-option">
                <span class="checkbox">@if($sanction->sanction_applique == 3)X@endif</span>
                Rupture du contrat
            </div>
        </div>

        <!-- Motifs Section -->
        <div class="motif-section">
            <div class="section-title">MOTIFS</div>
            <div style="padding: 10px; line-height: 1.6;">
                Le {{ $frdate }} {{ $sanction->expose_motif }}
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line">S. KOULIBALY</div>
                <div style="font-size: 12px; margin-top: 5px;">Direction des Ressources Humaines</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">Notification au travailleur</div>
                <div style="font-size: 12px; margin-top: 5px;">Matricule, Date et Signature</div>
            </div>
        </div>
    </div>
</body>
</html>
