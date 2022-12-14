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
        eventClick: function (info) {
            const modalBackdrop = document.getElementById('modalBackdrop')

            modalBackdrop.classList.remove('hidden')
            modalBackdrop.classList.add('block')
        }
    })
    calendar.render()

    const result = await window
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

    const eventSources = []
    result.calendarResources.forEach((resource) => {
        eventSources.push({
            title: '勤務',
            start: resource.start_time,
            end: resource.end_time,
            constraint: 'businessHours'
        })
    })

    calendar.addEventSource(eventSources)
})
