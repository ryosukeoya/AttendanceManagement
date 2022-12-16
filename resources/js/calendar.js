import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'
import { format, subMinutes } from 'date-fns'

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
        dayCellContent: function (e) {
            return (e.dayNumberText = e.dayNumberText.replace('日', ''))
        },
        eventClick: function (info) {
            const startDate = info.event._instance.range.start
            const endDate = info.event._instance.range.end

            openModal()
            createModalContent(startDate, endDate)
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

const subTimezoneDiff = (date) => {
    const timezoneOffset = date.getTimezoneOffset()
    const subedDate = subMinutes(date, -timezoneOffset)
    return subedDate
}

const openModal = () => {
    const modalBackdrop = document.getElementById('modalBackdrop')

    modalBackdrop.classList.remove('hidden')
    modalBackdrop.classList.add('block')
}

const createModalContent = (startDate, endDate) => {
    const startElm = document.getElementById('workingStartTime')
    const endElm = document.getElementById('workingEndTime')
    const totalElm = document.getElementById('WorkingTotalTime')

    // BACKGROUND
    // startDateがJSTのタイムゾーンの時間分加算されるので、subTimezoneDiffでタイムゾーン分減算した。理由わからず
    // 再現手順　
    const localStartDate = subTimezoneDiff(startDate)
    const formatedStartDate = format(localStartDate, 'yyyy年MM月dd日 HH:mm:ss')
    startElm.textContent = `勤務開始時間 : ${formatedStartDate}`

    const localEndDate = subTimezoneDiff(endDate)
    const formatedEndDate = format(localEndDate, 'yyyy年MM月dd日 HH:mm:ss')
    endElm.textContent = `勤務終了時間 : ${formatedEndDate}`

    const totalMilliSeconds = endDate - startDate
    const totalSeconds = totalMilliSeconds / 1000

    const secondsPerHour = 3600
    const minute = 60
    const totalHours = Math.floor(totalSeconds / secondsPerHour)
    const totalMinutes = (totalSeconds % secondsPerHour) / minute

    totalElm.textContent = `合計勤務時間 : ${totalHours}時間${totalMinutes}分`
}
