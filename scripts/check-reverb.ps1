$ErrorActionPreference = 'Stop'

try {
    $projectRoot = Split-Path -Parent $PSScriptRoot
    $keyLine = Get-Content -LiteralPath (Join-Path $projectRoot '.env') |
        Where-Object { $_ -match '^REVERB_APP_KEY=' } |
        Select-Object -First 1
    $appKey = ($keyLine -replace '^REVERB_APP_KEY=', '').Trim().Trim('"').Trim("'")

    if ([string]::IsNullOrWhiteSpace($appKey)) {
        throw 'REVERB_APP_KEY is missing from .env.'
    }

    $client = [System.Net.Sockets.TcpClient]::new()
    $connect = $client.BeginConnect('127.0.0.1', 8080, $null, $null)
    if (-not $connect.AsyncWaitHandle.WaitOne(2000)) {
        throw 'Timed out connecting to port 8080.'
    }
    $client.EndConnect($connect)

    $stream = $client.GetStream()
    $stream.ReadTimeout = 2000
    $request = "GET /app/$appKey`?protocol=7&client=skillhub-healthcheck&version=1.0 HTTP/1.1`r`nHost: 127.0.0.1:8080`r`nUpgrade: websocket`r`nConnection: Upgrade`r`nSec-WebSocket-Key: MTIzNDU2Nzg5MDEyMzQ1Ng==`r`nSec-WebSocket-Version: 13`r`nOrigin: http://127.0.0.1:8000`r`n`r`n"
    $bytes = [Text.Encoding]::ASCII.GetBytes($request)
    $stream.Write($bytes, 0, $bytes.Length)

    $buffer = New-Object byte[] 2048
    $read = $stream.Read($buffer, 0, $buffer.Length)
    $response = [Text.Encoding]::ASCII.GetString($buffer, 0, $read)
    $client.Dispose()

    if ($response -notmatch '^HTTP/1\.[01] 101 ') {
        throw "Port 8080 returned an unexpected response: $($response.Split("`r`n")[0])"
    }

    Write-Output 'SkillHub Reverb is ready on ws://127.0.0.1:8080.'
    exit 0
} catch {
    Write-Error $_.Exception.Message
    exit 1
}
