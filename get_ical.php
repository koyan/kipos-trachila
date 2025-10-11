<?php
header('Content-Type: application/json');

// Your iCal URL
$ical_url = 'https://ical.booking.com/v1/export?t=fa0b65eb-870a-441f-9c32-01a38656038e';

// Fetch the iCal data
$ical_data = file_get_contents($ical_url);

$lines = explode("\n", $ical_data);
$events = [];
$event = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line == 'BEGIN:VEVENT') {
        $event = [];
    } elseif ($line == 'END:VEVENT') {
        if (isset($event['DTSTART']) && isset($event['DTEND'])) {
            $events[] = [
                'start' => $event['DTSTART'],
                'end' => $event['DTEND']
            ];
        }
    } else {
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $key = preg_replace('/;.*$/', '', $key); // Remove parameters
            $event[$key] = $value;
        }
    }
}
echo json_encode($events);