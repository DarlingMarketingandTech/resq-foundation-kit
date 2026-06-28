# Sync resq-core and resq-clean-pro from repo to LocalWP runtime.
# Run from repo root: .\scripts\sync-to-localwp.ps1

$ErrorActionPreference = 'Stop'

$repoRoot = Split-Path -Parent $PSScriptRoot
$localWpContent = 'C:\Users\hoosi\Local Sites\resq-foundation-kit\app\public\wp-content'

$themeSrc = Join-Path $repoRoot 'wp-content\themes\resq-clean-pro'
$themeDst = Join-Path $localWpContent 'themes\resq-clean-pro'
$pluginSrc = Join-Path $repoRoot 'wp-content\plugins\resq-core'
$pluginDst = Join-Path $localWpContent 'plugins\resq-core'

if (-not (Test-Path $themeSrc)) {
    throw "Repo theme not found: $themeSrc"
}
if (-not (Test-Path $pluginSrc)) {
    throw "Repo plugin not found: $pluginSrc"
}
if (-not (Test-Path $localWpContent)) {
    throw "LocalWP wp-content not found: $localWpContent"
}

Write-Host "Syncing theme..."
robocopy $themeSrc $themeDst /MIR /XD node_modules .git /NFL /NDL /NJH /NJS /nc /ns /np
if ($LASTEXITCODE -ge 8) { throw "robocopy theme failed with exit $LASTEXITCODE" }

Write-Host "Syncing plugin..."
robocopy $pluginSrc $pluginDst /MIR /XD node_modules .git vendor /NFL /NDL /NJH /NJS /nc /ns /np
if ($LASTEXITCODE -ge 8) { throw "robocopy plugin failed with exit $LASTEXITCODE" }

Write-Host ""
Write-Host "Sync complete. In LocalWP site shell (WordPress root), run:"
Write-Host "  wp cache flush"
Write-Host "  wp rewrite flush"
Write-Host "  wp resq routes audit"
Write-Host "  wp resq lanes audit"
