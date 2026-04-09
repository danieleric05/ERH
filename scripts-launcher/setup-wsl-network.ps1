# Script PowerShell pour configurer l'accès réseau à ERH via WSL2
# Exécuter en tant qu'Administrateur

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Configuration réseau WSL2 pour ERH" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# 1. Récupérer l'IP de WSL2
Write-Host "1. Récupération de l'IP WSL2..." -ForegroundColor Yellow
$wslIP = (wsl hostname -I).Trim()
Write-Host "   IP WSL2 : $wslIP" -ForegroundColor Green

# 2. Récupérer l'IP Windows sur le réseau
Write-Host ""
Write-Host "2. Récupération de l'IP Windows..." -ForegroundColor Yellow
$windowsIP = (Get-NetIPAddress | Where-Object {$_.InterfaceAlias -notlike "*WSL*" -and $_.AddressFamily -eq "IPv4" -and $_.IPAddress -notlike "127.*"} | Select-Object -First 1).IPAddress
Write-Host "   IP Windows : $windowsIP" -ForegroundColor Green

# 3. Supprimer les anciennes règles de port forwarding (si elles existent)
Write-Host ""
Write-Host "3. Nettoyage des anciennes règles..." -ForegroundColor Yellow
try {
    netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0 | Out-Null
    netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=$windowsIP | Out-Null
} catch {
    # Ignorer les erreurs si les règles n'existent pas
}
Write-Host "   Nettoyage terminé" -ForegroundColor Green

# 4. Créer la règle de port forwarding
Write-Host ""
Write-Host "4. Création du port forwarding..." -ForegroundColor Yellow
Write-Host "   Windows :8000 -> WSL2 ($wslIP):8000" -ForegroundColor Cyan

netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=$wslIP

if ($LASTEXITCODE -eq 0) {
    Write-Host "   Port forwarding créé avec succès !" -ForegroundColor Green
} else {
    Write-Host "   Erreur lors de la création du port forwarding" -ForegroundColor Red
    exit 1
}

# 5. Configurer le pare-feu Windows
Write-Host ""
Write-Host "5. Configuration du pare-feu Windows..." -ForegroundColor Yellow

# Supprimer l'ancienne règle si elle existe
try {
    Remove-NetFirewallRule -DisplayName "Laravel ERH Server" -ErrorAction SilentlyContinue | Out-Null
} catch {
    # Ignorer
}

# Créer la nouvelle règle
New-NetFirewallRule -DisplayName "Laravel ERH Server" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow | Out-Null

if ($?) {
    Write-Host "   Règle pare-feu créée avec succès !" -ForegroundColor Green
} else {
    Write-Host "   Erreur lors de la création de la règle pare-feu" -ForegroundColor Red
}

# 6. Afficher la configuration actuelle
Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Configuration terminée !" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Règles de port forwarding actives :" -ForegroundColor Yellow
netsh interface portproxy show all
Write-Host ""
Write-Host "Adresses d'accès :" -ForegroundColor Yellow
Write-Host "  - Localhost       : http://localhost:8000" -ForegroundColor White
Write-Host "  - IP Windows      : http://$windowsIP:8000" -ForegroundColor White
Write-Host "  - IP WSL2 (direct): http://$wslIP:8000" -ForegroundColor White
Write-Host ""
Write-Host "Partagez l'IP suivante avec vos collègues :" -ForegroundColor Green
Write-Host "  http://$windowsIP:8000" -ForegroundColor Cyan -BackgroundColor DarkGreen
Write-Host ""
Write-Host "Note : Assurez-vous que le serveur Laravel tourne dans WSL2 avec :" -ForegroundColor Yellow
Write-Host "  cd /home/daniel/work/projects/erh/site" -ForegroundColor White
Write-Host "  ./start-server-network.sh" -ForegroundColor White
Write-Host ""
