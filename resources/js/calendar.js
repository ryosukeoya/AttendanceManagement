/**
 * TODO プリント勤務
 * @see https://preview.keenthemes.com/good/documentation/general/fullcalendar/background-events.html
 */ 
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar')
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        // initialDate: '2020-09-12'
        events: [
            {
                title: '勤務',
                start: '2022-10-24T13:00:00',
                constraint: 'businessHours'
            }
        ]
    })
    calendar.render()
})
