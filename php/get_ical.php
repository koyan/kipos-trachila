<?php
header('Content-Type: application/json');

// Your iCal URL
$ical_url = 'https://www.airbnb.com/calendar/ical/1312892551612331429.ics?s=124a376ef352c8ff73bf6f805e60248f';

// Fetch the iCal data
$ical_data = file_get_contents($ical_url);
if ($ical_data === false) {
    echo json_encode([]);
    exit;
}

$lines = explode("\n", $ical_data);
$events = [];
$event = [];

foreach ($lines as $line) {
    $line = trim($line);
    if ($line === 'BEGIN:VEVENT') {
        $event = [];
    } elseif ($line === 'END:VEVENT') {
        if (!empty($event['DTSTART']) && !empty($event['DTEND'])) {
            // Convert to YYYY-MM-DD
            $start = preg_replace('/T.*$/', '', $event['DTSTART']); // remove time if present
            $end   = preg_replace('/T.*$/', '', $event['DTEND']);

            $startDate = DateTime::createFromFormat('Ymd', $start);
            $endDate   = DateTime::createFromFormat('Ymd', $end);

            if ($startDate && $endDate) {
                $events[] = [
                    'start' => $startDate->format('Y-m-d'),
                    'end'   => $endDate->format('Y-m-d')
                ];
            }
        }
    } else {
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $key = preg_replace('/;.*$/', '', $key); // remove parameters like TZID
            $event[$key] = $value;
        }
    }
}

echo json_encode($events);
