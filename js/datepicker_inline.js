$(function() {
  "use strict";

  // Fetch booked dates from server first
  fetch('php/get_ical.php')
    .then(res => res.json())
    .then(events => {
      console.log('Booked dates from iCal:', events);

      // Convert JSON to Easepick DateTime objects
      const bookedDates = events.map(ev => {
        const start = new easepick.DateTime(ev.start, 'YYYY-MM-DD');
        const end   = new easepick.DateTime(ev.end, 'YYYY-MM-DD');
        return [start, end];
      });

      // Now create the picker with the booked dates
      const picker = new easepick.create({
        element: document.getElementById('date_booking'),
        css: ['css/daterangepicker_v2.css'],
        lang: 'en-EN',
        format: "DD/MM/YYYY",
        calendars: 2,
        grid: 2,
        zIndex: 10,
        inline: true,
        plugins: ['LockPlugin', 'RangePlugin'],
        RangePlugin: {
          tooltipNumber(num) { return num - 1; },
          locale: { one: 'night', other: 'nights' }
        },
        LockPlugin: {
          minDate: new Date(),
          minDays: 1,
          inseparable: false,
          filter(date, picked) {
            if (picked.length === 1) {
              const incl = date.isBefore(picked[0]) ? '[)' : '(]';
              return !picked[0].isSame(date, 'day') && date.inArray(bookedDates, incl);
            }
            return date.inArray(bookedDates, '[)');
          }
        }
      });
    })
    .catch(err => console.error('Error fetching booked dates:', err));
});

