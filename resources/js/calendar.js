/**
 * TODO プリント勤務
 * @see https://preview.keenthemes.com/good/documentation/general/fullcalendar/background-events.html
 */
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar')
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        events: [
            {
                title: '勤務',
                start: '2022-10-24T13:00:00',
                end: '2022-10-24T15:00:00',
                constraint: 'businessHours'
            }
        ],
        dateClick: function (info) {
            const modalBackdrop = document.getElementById('modalBackdrop')

            modalBackdrop.classList.remove('hidden')
            modalBackdrop.classList.add('block')
        }
    })
    calendar.render()
})
