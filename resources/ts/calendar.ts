import { Calendar, DayCellContentArg, EventClickArg } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'
import { differenceInMinutes, format, subMinutes } from 'date-fns'

type CalendarResource = {
    title: string
    start_time: Date
    end_time: Date
    total_minutes: number
}

type CalendarResources = Array<CalendarResource>

type CalendarResourcesApi = {
    calendarResources: CalendarResources
}

/**
 * TODO プリント勤務
 * @see https://preview.keenthemes.com/good/documentation/general/fullcalendar/background-events.html
 */
document.addEventListener('DOMContentLoaded', async function () {
    const calendarEl = document.getElementById('calendar')
    if (!calendarEl) return
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true,
        businessHours: true,
        locale: 'ja',
        timeZone: 'local',
        dayCellContent: function (e: DayCellContentArg) {
            return (e.dayNumberText = e.dayNumberText.replace('日', ''))
        },
        eventClick: function (info: EventClickArg) {
            const startDate = info?.event?._instance?.range.start
            const endDate = info?.event?._instance?.range.end

            openModal()
            createModalContent(startDate, endDate)
        }
    })
    calendar.render()

    try {
        const result: CalendarResourcesApi = await window
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

        const eventSources = result.calendarResources.map((resource: CalendarResource) => {
            // @see https://fullcalendar.io/docs/event-object
            return {
                title: '勤務',
                start: resource.start_time,
                end: resource.end_time,
                total_minutes: resource.total_minutes,
                constraint: 'businessHours',
                // BACK_GROUND 終了時間未登録のものは見せない
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

const subTimezoneDiff = (date: Date) => {
    const timezoneOffset = date.getTimezoneOffset()
    const subedDate = subMinutes(date, -timezoneOffset)
    return subedDate
}

const openModal = () => {
    const modalBackdrop = document.getElementById('modalBackdrop')

    modalBackdrop?.classList.remove('hidden')
    modalBackdrop?.classList.add('block')
}

const createModalContent = (startDate?: Date, endDate?: Date) => {
    const startElm = document.getElementById('workingStartTime')
    const endElm = document.getElementById('workingEndTime')
    const totalElm = document.getElementById('WorkingTotalTime')

    // BACKGROUND
    // startDateがJSTのタイムゾーンの時間分加算されるので、subTimezoneDiffでタイムゾーン分減算した。理由わからず
    // 再現手順　https://github.com/ryosuke1256/AttendanceManagement/issues/26

    if (startElm && startDate) {
        const localStartDate = subTimezoneDiff(startDate)
        const formatedStartDate = format(localStartDate, 'yyyy年MM月dd日 HH:mm:ss')
        startElm.textContent = `勤務開始時間 : ${formatedStartDate}`
    }

    if (endElm && endDate) {
        const localEndDate = subTimezoneDiff(endDate)
        const formatedEndDate = format(localEndDate, 'yyyy年MM月dd日 HH:mm:ss')
        endElm.textContent = `勤務終了時間 : ${formatedEndDate}`
    }

    if (totalElm && startDate && endDate) {
        const diffMinutes = differenceInMinutes(endDate, startDate)

        const minutesInHour = 60
        const totalHours = Math.floor(diffMinutes / minutesInHour)
        const totalMinutes = diffMinutes % minutesInHour 

        totalElm.textContent = `合計勤務時間 : ${totalHours}時間${totalMinutes}分`
    }
}
