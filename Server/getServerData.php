<?php
// API-KEY
$api_key = 'ENTER_A_API_KEY';

if (!isset($_POST['api_key']) || $_POST['api_key'] !== $api_key) {
    header('HTTP/1.0 401 Unauthorized');
    die('Wrong API-Key');
}

function executeCommand($command) {
// Ausführung des Befehls
    $output = shell_exec($command);
    return trim($output);
}

// CPU-Auslastung pro Kern
function getCpuLoadPerCore() {
    // Anzahl der Cores ermitteln
    
    $numberOfCores = intval(executeCommand('nproc'));

    // CPU-Daten am Anfang und Ende eines Zeitintervalls erfassen
    $cpuDataStart = explode("\n", executeCommand('cat /proc/stat'));
    sleep(1); // Warte eine Sekunde für den Vergleich
    $cpuDataEnd = explode("\n", executeCommand('cat /proc/stat'));

    $cpuLoads = [];
    for ($i = 0; $i < $numberOfCores; $i++) {
        $startLine = preg_split('/\s+/', trim($cpuDataStart[$i + 1]));
        $endLine = preg_split('/\s+/', trim($cpuDataEnd[$i + 1]));

        // Berechnen der Unterschiede
        $diffIdle = $endLine[4] - $startLine[4];
        $diffTotal = array_sum(array_slice($endLine, 1, 7)) - array_sum(array_slice($startLine, 1, 7));
        $diffLoad = $diffTotal - $diffIdle;

        // Berechnen der Auslastung für jeden Kern
        if ($diffTotal > 0) {
            $cpuLoads['core_' . $i] = ($diffLoad / $diffTotal) * 100; // Auslastung in Prozent
        } else {
            $cpuLoads['core_' . $i] = 0;
        }
    }

    return $cpuLoads;
}



// RAM-Auslastung und Gesamtkapazität
function getRamUsage() {
    $output = executeCommand('free -m');
    preg_match('/Mem:\s+(\d+)\s+(\d+)/', $output, $matches);
    return array(
        'used' => floatval($matches[2]),
        'total' => floatval($matches[1]),
        'usagePercent' => round(($matches[2] / $matches[1]) * 100, 2)
    );
}

// Festplattenauslastung und Gesamtkapazität
function getDiskUsage() {
    $output = executeCommand('df -h /');
    preg_match('/\s+(\d+\.?\d*)[GTP]\s+(\d+\.?\d*)[GTP]/', $output, $matches);
    return array(
        'used' => $matches[2],
        'total' => $matches[1],
        'usagePercent' => round(($matches[2] / $matches[1]) * 100, 2)
    );
}

// Netzwerkauslastung
function getNetworkTraffic() {
    // RX-Daten vor und nach einer Sekunde erfassen
    $networkRxBefore = intval(shell_exec("cat /sys/class/net/eth0/statistics/rx_bytes"));
    sleep(1);
    $networkRxAfter = intval(shell_exec("cat /sys/class/net/eth0/statistics/rx_bytes"));

    // TX-Daten vor und nach einer Sekunde erfassen
    $networkTxBefore = intval(shell_exec("cat /sys/class/net/eth0/statistics/tx_bytes"));
    $networkTxAfter = intval(shell_exec("cat /sys/class/net/eth0/statistics/tx_bytes"));

    // Übertragungsraten berechnen (in KB/s)
    $networkRxRate = ($networkRxAfter - $networkRxBefore) / 1024;  // RX Rate in KB/s
    $networkTxRate = ($networkTxAfter - $networkTxBefore) / 1024;  // TX Rate in KB/s

    return array(
        'rx_rate' => $networkRxRate,
        'tx_rate' => $networkTxRate
    );
}


// Systeminformationen
function getSystemInfo() {
    return array(
        'os' => executeCommand('uname -a'),
        'uptime' => executeCommand('uptime')
    );
}

// Erstellen des JSON-Objekts
$data = array(
    'ip' => 'localhost',
    'cpuLoads' => getCpuLoadPerCore(),
    'ram' => getRamUsage(),
    'disk' => getDiskUsage(),
    'network' => getNetworkTraffic(),
    'systemInfo' => getSystemInfo()
);

// Senden der JSON-Daten
header('Content-Type: application/json');
echo json_encode($data);
?>
