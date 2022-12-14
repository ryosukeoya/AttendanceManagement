import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'

/**
 * TODO プリント勤務
 * @see https://preview.keenthemes.com/good/documentation/general/fullcalendar/background-events.html
 */
document.addEventListener('DOMContentLoaded', async function () {
    const calendarEl = document.getElementById('calendar')
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        dateClick: function (info) {
            const modalBackdrop = document.getElementById('modalBackdrop')

            modalBackdrop.classList.remove('hidden')
            modalBackdrop.classList.add('block')
        }
    })
    calendar.render()

    const calendarResources = await window
        .fetch('api_calendar', { method: 'GET' })
        .then((res) => {
            return res.json()
        })
        .then((result) => {
            return result
        })
        .catch((error) => {
            console.error(error)
        })

    calendar.addEvent({
        title: '肉体労働',
        start: '2022-12-15T13:00:00',
        end: '2022-12-15T15:00:00',
        constraint: 'businessHours'
    })
})
