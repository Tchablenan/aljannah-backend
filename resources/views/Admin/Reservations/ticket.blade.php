<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation {{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .header {
            border-bottom: 3px solid #0066cc;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .logo-section {
            display: table;
            width: 100%;
        }
        
        .logo {
            display: table-cell;
            vertical-align: middle;
            width: 200px;
        }
        
        .company-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        
        /* Document Title */
        .document-title {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
        }
        
        .document-title h1 {
            font-size: 28px;
            color: #0066cc;
            margin-bottom: 10px;
        }
        
        .reference {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-top: 10px;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Main Content */
        .content {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .client-info, .flight-info {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .client-info {
            margin-right: 2%;
            background: #f8f9fa;
        }
        
        .flight-info {
            margin-left: 2%;
            background: #fff;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .info-row {
            margin-bottom: 12px;
            display: table;
            width: 100%;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #555;
            width: 40%;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            color: #333;
            vertical-align: top;
        }
        
        /* Flight Route */
        .route-section {
            text-align: center;
            margin: 30px 0;
            padding: 25px;
            background: #0066cc;
            color: white;
            border-radius: 8px;
        }
        
        .route-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .route-details {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        
        .route-arrow {
            margin: 0 15px;
            font-size: 18px;
        }
        
        .route-dates {
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.9;
        }
        
        /* Jet Information */
        .jet-section {
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #0066cc;
            border-radius: 8px;
            background: #f8f9fa;
        }
        
        /* Message Section */
        .message-section {
            margin: 30px 0;
            padding: 20px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }
        
        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .footer-info {
            margin-bottom: 10px;
        }
        
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .barcode {
            margin: 20px 0;
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-20 { margin-top: 20px; }
        .mb-20 { margin-bottom: 20px; }
        
        /* Print Styles */
        @media print {
            body { font-size: 12px; }
            .container { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <!-- Remplacez par votre logo -->
                    <div style="width: 80px; height: 80px; background: #0066cc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px;">
                        AL
                    </div>
                </div>
                <div class="company-info">
                    <div class="company-name">Aljannah Airlines</div>
                    <div class="company-tagline">Excellence in Private Aviation</div>
                    <div style="margin-top: 10px; font-size: 11px; color: #888;">
                        <div>Email: contact@aljannah-airlines.com</div>
                        <div>Tél: +33 1 23 45 67 89</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">
            <h1>CONFIRMATION DE RÉSERVATION</h1>
            <div class="reference">Référence: REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</div>
            
            @if($reservation->status === 'confirmed')
                <div class="status-badge status-confirmed">Confirmée</div>
            @elseif($reservation->status === 'pending')
                <div class="status-badge status-pending">En attente</div>
            @else
                <div class="status-badge status-cancelled">Annulée</div>
            @endif
        </div>

        <!-- Flight Route -->
        <div class="route-section">
            <div class="route-title">ITINÉRAIRE</div>
            <div class="route-details">
                {{ $reservation->departure_location }}
                <span class="route-arrow">✈</span>
                {{ $reservation->arrival_location }}
            </div>
            <div class="route-dates">
                Départ: {{ $reservation->departure_date->format('d/m/Y à H:i') }}
                @if($reservation->arrival_date->format('Y-m-d') !== $reservation->departure_date->format('Y-m-d'))
                    | Retour: {{ $reservation->arrival_date->format('d/m/Y à H:i') }}
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Client Information -->
            <div class="client-info">
                <div class="section-title">INFORMATIONS CLIENT</div>
                
                <div class="info-row">
                    <div class="info-label">Nom complet:</div>
                    <div class="info-value">{{ $reservation->full_name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $reservation->email }}</div>
                </div>
                
                @if($reservation->phone)
                <div class="info-row">
                    <div class="info-label">Téléphone:</div>
                    <div class="info-value">{{ $reservation->phone }}</div>
                </div>
                @endif
                
                <div class="info-row">
                    <div class="info-label">Passagers:</div>
                    <div class="info-value">{{ $reservation->passengers }} personne(s)</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Date de réservation:</div>
                    <div class="info-value">{{ $reservation->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>

            <!-- Flight Information -->
            <div class="flight-info">
                <div class="section-title">DÉTAILS DU VOL</div>
                
                <div class="info-row">
                    <div class="info-label">Date de départ:</div>
                    <div class="info-value">{{ $reservation->departure_date->format('d/m/Y à H:i') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Date d'arrivée:</div>
                    <div class="info-value">{{ $reservation->arrival_date->format('d/m/Y à H:i') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Lieu de départ:</div>
                    <div class="info-value">{{ $reservation->departure_location }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Destination:</div>
                    <div class="info-value">{{ $reservation->arrival_location }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">
                        @if($reservation->status === 'confirmed')
                            Confirmée
                        @elseif($reservation->status === 'pending')
                            En attente de confirmation
                        @else
                            Annulée
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Jet Information -->
        @if($reservation->jet)
        <div class="jet-section">
            <div class="section-title">AÉRONEF ASSIGNÉ</div>
            
            <div class="info-row">
                <div class="info-label">Modèle:</div>
                <div class="info-value">{{ $reservation->jet->nom }}</div>
            </div>
            
            @if($reservation->jet->modele)
            <div class="info-row">
                <div class="info-label">Type:</div>
                <div class="info-value">{{ $reservation->jet->modele }}</div>
            </div>
            @endif
            
            @if($reservation->jet->capacite)
            <div class="info-row">
                <div class="info-label">Capacité:</div>
                <div class="info-value">{{ $reservation->jet->capacite }} passagers</div>
            </div>
            @endif
        </div>
        @else
        <div class="jet-section">
            <div class="section-title">TYPE D'AÉRONEF</div>
            <div class="info-value">Type demandé: Jet privé</div>
            <div style="margin-top: 10px; font-size: 12px; color: #666;">
                L'aéronef spécifique sera confirmé ultérieurement.
            </div>
        </div>
        @endif

        <!-- Message -->
        @if($reservation->message)
        <div class="message-section">
            <div class="section-title" style="color: #856404;">DEMANDES SPÉCIALES</div>
            <div style="margin-top: 10px;">{{ $reservation->message }}</div>
        </div>
        @endif

        <!-- QR Code / Barcode -->
        <div class="qr-section">
            <div style="font-weight: bold; margin-bottom: 10px;">Code de réservation</div>
            <div class="barcode">
                <div style="font-family: 'Courier New', monospace; font-size: 16px; font-weight: bold; letter-spacing: 2px; border: 1px solid #333; padding: 10px; display: inline-block;">
                    REF{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
                </div>
            </div>
            <div style="font-size: 11px; color: #666;">
                Présentez ce code lors de votre embarquement
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                <strong>Aljannah Airlines</strong> - Service de jet privé de luxe
            </div>
            <div class="footer-info">
                En cas de questions, contactez-nous au +33 1 23 45 67 89 ou contact@aljannah-airlines.com
            </div>
            <div style="margin-top: 15px; font-size: 11px;">
                Document généré le {{ now()->format('d/m/Y à H:i') }}
            </div>
            
            @if($reservation->status === 'confirmed')
            <div style="margin-top: 20px; padding: 15px; background: #d4edda; border-radius: 5px; color: #155724;">
                <strong>Votre réservation est confirmée !</strong><br>
                Nous vous contacterons 24h avant le départ pour les détails finaux.
            </div>
            @endif
        </div>
    </div>
</body>
</html>