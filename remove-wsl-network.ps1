# Script PowerShell pour supprimer la configuration réseau WSL2 pour ERH
# Exécuter en tant qu'Administrateur

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Suppression configuration réseau WSL2" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# 1. Supprimer le port forwarding
Write-Host "1. Suppression du port forwarding..." -ForegroundColor Yellow
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0

if ($LASTEXITCODE -eq 0) {
    Write-Host "   Port forwarding supprimé avec succès !" -ForegroundColor Green
} else {
    Write-Host "   Aucun port forwarding à supprimer" -ForegroundColor Yellow
}

# 2. Supprimer la règle pare-feu
Write-Host ""
Write-Host "2. Suppression de la règle pare-feu..." -ForegroundColor Yellow
try {
    Remove-NetFirewallRule -DisplayName "Laravel ERH Server" -ErrorAction Stop
    Write-Host "   Règle pare-feu supprimée avec succès !" -ForegroundColor Green
} catch {
    Write-Host "   Aucune règle pare-feu à supprimer" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Nettoyage terminé !" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
