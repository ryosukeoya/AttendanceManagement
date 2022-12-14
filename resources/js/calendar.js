'use strict'

import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'
import { getHours, getMinutes } from 'date-fns'

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

            const start = document.getElementById('start')
            const end = document.getElementById('end')
            const total = document.getElementById('total')

            const startHours = getHours(info.event._instance.range.start)
            const startMinutes = getMinutes(info.event._instance.range.start)
            start.textContent = `勤務開始時間 : ${startHours}時${startMinutes}分`

            const endHours = getHours(info.event._instance.range.end)
            const endMinutes = getMinutes(info.event._instance.range.end)
            end.textContent = `勤務終了時間 : ${endHours}時${endMinutes}分`

            // TODO Refactor
            // TODO　日付跨ぎ
            const totalMilliSeconds = info.event._instance.range.end - info.event._instance.range.start
            console.log(totalMilliSeconds)
            const totalSeconds = totalMilliSeconds / 1000
            console.log(totalSeconds)
            const totalHours = Math.floor(totalSeconds / 3600)
            const totalMinutes = (totalSeconds % 3600) / 60

            total.textContent = `合計勤務時間 : ${totalHours}時間${totalMinutes}分`
        }
    })
    calendar.render()

    try {
        const result = await window
            .fetch('api_attendance_record/me/all', { method: 'GET' })
            .then((res) => {
                if (!res.ok) {
                    console.error(res.status, 'server error')
                }
                return res.json()
            })
            .then((result) => {
                return result
            })
            .catch((error) => {
                console.error(error)
            })

        const eventSources = result.calendarResources.map((resource) => {
            return {
                title: '勤務',
                start: resource.start_time,
                end: resource.end_time,
                constraint: 'businessHours'
            }
        })

        calendar.addEventSource(eventSources)
    } catch (error) {
        console.error(error)
    }
})
