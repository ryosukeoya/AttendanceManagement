import { Calendar, DayCellContentArg, EventClickArg } from '@fullcalendar/core'
import { differenceInMinutes, format } from 'date-fns'

import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import listPlugin from '@fullcalendar/list'
import timeGridPlugin from '@fullcalendar/timegrid'

type CalendarResource = {
    title: string
    start_time: string
    end_time: string
    total_minutes: number
}

type CalendarResourcesApi = {
    calendarResources: Array<CalendarResource>
}

document.addEventListener('DOMContentLoaded', async () => {
    const calendarElm = document.getElementById('calendar')!

    const calendar = new Calendar(calendarElm, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        businessHours: true,
        locale: 'ja',
        timeZone: 'Asia/Tokyo',
        dayCellContent: (e: DayCellContentArg) => (e.dayNumberText = e.dayNumberText.replace('日', '')),
        eventClick: (info: EventClickArg) => {
            const startDate = info.event._instance?.range.start
            const endDate = info.event._instance?.range.end

            const modalBackdrop = document.getElementById('modalBackdrop')!

            modalBackdrop.classList.remove('hidden')
            modalBackdrop.classList.add('block')

            createModalContent(startDate, endDate)
        }
    })
    calendar.render()

    try {
        const res = fetch('api_attendance_record/me/all', { method: 'GET' })
        const data: CalendarResourcesApi = await res.then((res) => {
            if (!res.ok) console.error(res.status, 'server error')
            return res.json()
        })

        const eventSources = data.calendarResources.map((resource: CalendarResource) => {
            return {
                title: '勤務',
                start: resource.start_time,
                end: resource.end_time,
                total_minutes: resource.total_minutes,
                constraint: 'businessHours',
                // 終了時間未登録のものは見せない
                display: !resource.end_time ? 'none' : 'auto'
            }
        })

        calendar.addEventSource(eventSources)
    } catch (error: unknown) {
        if (error instanceof Error) {
            console.error(error.message)
        }
    }
})

const createModalContent = (startDate?: Date, endDate?: Date) => {
    const startElm = document.getElementById('workingStartTime')!,
        endElm = document.getElementById('workingEndTime')!,
        totalElm = document.getElementById('WorkingTotalTime')!,
        FORMAT_CONF = 'yyyy年MM月dd日 HH:mm:ss'

    if (startDate) startElm.textContent = `勤務開始時間 : ${format(startDate, FORMAT_CONF)}`
    if (endDate) endElm.textContent = `勤務終了時間 : ${format(endDate, FORMAT_CONF)}`
    if (startDate && endDate) {
        const diffMinutes = differenceInMinutes(endDate, startDate),
            MINUTES_IN_HOUR = 60

        const totalHours = Math.floor(diffMinutes / MINUTES_IN_HOUR)
        const totalMinutes = diffMinutes % MINUTES_IN_HOUR

        totalElm.textContent = `合計勤務時間 : ${totalHours}時間${totalMinutes}分`
    }
}
