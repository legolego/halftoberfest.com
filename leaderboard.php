<?php
// Strike of '86 leaderboard. Scores are saved in scores.txt, one per line: INITIALS,POINTS,DATE
header('Content-Type: application/json');
header('Cache-Control: no-store');

$file = __DIR__ . '/scores.txt';
$blocked = array('ASS', 'FUK', 'FUC', 'FCK', 'FAG', 'CUM', 'DIK', 'DIC', 'KKK', 'NIG', 'NGR', 'NGA', 'SEX',
    'TIT', 'COK', 'CUK', 'CNT', 'VAG', 'PUS', 'HOE', 'SUK', 'XXX', 'NAZ', 'KYS', 'JIZ', 'FAP', 'RAP', 'SHT', 'BCH');

function top_scores($file) {
    $rows = array();
    foreach (@file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: array() as $line) {
        $p = explode(',', trim($line));
        if (count($p) === 3) $rows[] = array('i' => $p[0], 's' => (int)$p[1], 'd' => $p[2]);
    }
    usort($rows, function ($a, $b) { return $b['s'] - $a['s']; });
    return array_slice($rows, 0, 10);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $in = json_decode(file_get_contents('php://input'), true);
    $ini = strtoupper(isset($in['initials']) ? (string)$in['initials'] : '');
    $score = isset($in['score']) ? $in['score'] : 0;

    $error = null;
    if (!preg_match('/^[A-Z]{3}$/', $ini)) $error = 'Initials must be 3 letters';
    elseif (in_array($ini, $blocked, true)) $error = 'Please pick different initials';
    elseif (!is_int($score) || $score < 1 || $score > 600) $error = 'Bad score';
    if ($error) {
        http_response_code(400);
        echo json_encode(array('error' => $error));
        exit;
    }

    if (file_put_contents($file, "$ini,$score," . date('Y-m-d') . "\n", FILE_APPEND | LOCK_EX) === false) {
        http_response_code(500);
        echo json_encode(array('error' => 'Could not save score'));
        exit;
    }

    $scores = top_scores($file);
    $place = null;
    foreach ($scores as $k => $s) {
        if ($s['i'] === $ini && $s['s'] === $score) { $place = $k + 1; break; }
    }
    echo json_encode(array('scores' => $scores, 'place' => $place));
} else {
    echo json_encode(array('scores' => top_scores($file)));
}
