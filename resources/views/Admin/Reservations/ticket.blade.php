<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket de réservation</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #ffffff;
        }

        .container {
            width: 700px;
            margin: 40px auto;
            border: 2px solid #30b9b4;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background-color: #30b9b4;
            color: white;
            padding: 20px 30px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 22px;
        }

        .section {
            padding: 30px;
            background-color: #e8f8f8;
        }

        .info-row {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            color: #222;
        }


        .value {
            width: 85%;
            text-align: right;
            color: #222;
            font-weight: 500;
        }
        .barcode {
            text-align: center;
            margin-top: 30px;
        }

        .barcode img {
            height: 50px;
        }

        .footer {
            padding: 20px 30px;
            background-color: #30b9b4;
            color: white;
            font-size: 12px;
            text-align: center;
        }

        .logo {
            height: 50px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
                        <div class="info-row"><span class="label">N° Réservation :</span class="value"> #{{ $reservation->id }}</div>
            <img src="{{ public_path('assets/media/logos/logo-2.png') }}" class="logo" alt="Logo Compagnie">
            <h2>Boarding Pass - Première Classe</h2>
        </div>

        <!-- Informations -->
        <div class="section">
            <div class="info-row"><span class="label">Nom :</span class="value"> {{ $reservation->first_name }} {{ $reservation->last_name }}</div>

            <div class="info-row"><span class="label">Date départ :</span class="value"> {{ \Carbon\Carbon::parse($reservation->departure_date)->format('d M Y') }}</div>
            <div class="info-row"><span class="label">Heure :</span class="value"> {{ \Carbon\Carbon::parse($reservation->departure_date)->format('H:i') }}</div>
            <div class="info-row"><span class="label">De :</span class="value"> {{ $reservation->departure_location }}</div>
            <div class="info-row"><span class="label">À :</span class="value"> {{ $reservation->arrival_location }}</div>
            <div class="info-row"><span class="label">Avion :</span class="value"> {{ $reservation->plane_type }}</div>
            <div class="info-row"><span class="label">Passagers :</span class="value"> {{ $reservation->passengers }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Merci d’avoir choisi notre service ✈️ <br>
            Bon vol avec <strong>Aljannah Airlines</strong> !
        </div>
    </div>
</body>
</html>
