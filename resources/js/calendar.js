'use strict'

import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'
import { format, subMinutes } from 'date-fns'

const subTimezoneDiff = (date) => {
    const timezoneOffset = date.getTimezoneOffset()
    const subedDate = subMinutes(date, -timezoneOffset)
    return subedDate
}

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
        businessHours: true,
        locale: 'ja',
        timeZone: 'local',
        eventClick: function (info) {
            const modalBackdrop = document.getElementById('modalBackdrop')

            modalBackdrop.classList.remove('hidden')
            modalBackdrop.classList.add('block')

            const start = document.getElementById('start')
            const end = document.getElementById('end')
            const total = document.getElementById('total')

            // startがJSTのタイムゾーンの時間分加算されるので、理由わからず
            // timeZone: 'local'、API 2022-12-15T00:00:00.000000Z(UTC表示)、calendar 12月15日9時表示(JTC時間)、eventClick(info) Thu Dec 15 2022 18:00:00 GMT+0900 (日本標準時)←なぜ？
            const localStartDate = subTimezoneDiff(info.event._instance.range.start)
            const startDate = format(localStartDate, 'yyyy年MM月dd日 HH:mm:ss')
            start.textContent = `勤務開始時間 : ${startDate}`

            const localEndDate = subTimezoneDiff(info.event._instance.range.end)
            const endDate = format(localEndDate, 'yyyy年MM月dd日 HH:mm:ss')
            end.textContent = `勤務終了時間 : ${endDate}`

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
