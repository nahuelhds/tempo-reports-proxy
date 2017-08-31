<?php
include 'vendor/autoload.php';

$serverUri = "__TEMPO_SERVER_URI__";
$tempoApiToken = "__TEMPO_API_TOKEN__";

$dateFrom = filter_input(INPUT_GET, 'from', FILTER_DEFAULT);
$dateTo = filter_input(INPUT_GET, 'to', FILTER_DEFAULT);

$parser = new \CristalMedia\TempoParser($serverUri, $tempoApiToken);
//$parser->setDateRange($from, $to);
try {
    $parser->setDateRange($dateFrom, $dateTo);
    $xlsString = $parser->getData();
    $parser->createCsvFromXlsString($xlsString);
    echo $parser->getCsv();

    $parser = null;
} catch (\RuntimeException $err) {
    die("Error: " . $err->getMessage());
}
