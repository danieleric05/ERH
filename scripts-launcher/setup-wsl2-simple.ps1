# Configuration simple port forwarding WSL2 -> Windows
# À exécuter en tant qu'Administrateur sur Windows PowerShell

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Configuration Port Forwarding WSL2" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Récupérer l'IP WSL2
Write-Host "Récupération IP WSL2..." -ForegroundColor Yellow
$wslIP = bash.exe -c "hostname -I | awk '{print `$1}'"
$wslIP = $wslIP.Trim()

if ([string]::IsNullOrEmpty($wslIP)) {
    Write-Host "ERREUR: Impossible de récupérer l'IP WSL2" -ForegroundColor Red
    exit 1
}

Write-Host "IP WSL2 détectée: $wslIP" -ForegroundColor Green
Write-Host ""

# Supprimer l'ancienne règle
Write-Host "Nettoyage anciennes règles..." -ForegroundColor Yellow
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0 2>$null
Write-Host "Fait." -ForegroundColor Green
Write-Host ""

# Créer le port forwarding
Write-Host "Création port forwarding 0.0.0.0:8000 -> ${wslIP}:8000..." -ForegroundColor Yellow
netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=$wslIP

if ($LASTEXITCODE -eq 0) {
    Write-Host "Port forwarding créé avec succès !" -ForegroundColor Green
} else {
    Write-Host "ERREUR lors de la création du port forwarding" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Configurer le pare-feu
Write-Host "Configuration pare-feu Windows..." -ForegroundColor Yellow
Remove-NetFirewallRule -DisplayName "ERH Laravel Port 8000" -ErrorAction SilentlyContinue 2>$null
New-NetFirewallRule -DisplayName "ERH Laravel Port 8000" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow | Out-Null

if ($?) {
    Write-Host "Pare-feu configuré avec succès !" -ForegroundColor Green
} else {
    Write-Host "Avertissement: Problème configuration pare-feu" -ForegroundColor Yellow
}
Write-Host ""

# Récupérer l'IP Windows
$windowsIP = (Get-NetIPAddress -AddressFamily IPv4 | Where-Object {$_.InterfaceAlias -notlike "*WSL*" -and $_.IPAddress -notlike "127.*" -and $_.PrefixOrigin -eq "Dhcp"} | Select-Object -First 1).IPAddress

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Configuration terminée !" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Configuration active:" -ForegroundColor Yellow
netsh interface portproxy show all
Write-Host ""
Write-Host "URLs d'accès:" -ForegroundColor Yellow
Write-Host "  Local       : http://localhost:8000" -ForegroundColor White
Write-Host "  IP Windows  : http://${windowsIP}:8000" -ForegroundColor White
Write-Host "  IP WSL2     : http://${wslIP}:8000" -ForegroundColor White
Write-Host ""
Write-Host "Partagez cette URL sur le réseau:" -ForegroundColor Green
Write-Host "  http://${windowsIP}:8000" -ForegroundColor Cyan -BackgroundColor DarkBlue
Write-Host ""
