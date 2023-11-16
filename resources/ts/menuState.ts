import { NullError } from './error'

type AttendanceStatus = number

type AttendanceStatusApi = { attendanceStatus: AttendanceStatus }

const changeMenuStateAccAttendanceStatus = (async () => {
    try {
        const res = fetch('api_attendance_record/me/today_status', { method: 'GET' })
        const data = await res.then((res: Response): Promise<AttendanceStatusApi> => {
            if (!res.ok) console.error(res.status, 'server error')
            return res.json()
        })
        const workingStartMenu = document.getElementById('workingStart')
        const workingEndMenu = document.getElementById('workingEnd')
        if (!workingStartMenu || !workingEndMenu) throw new NullError()

        switch (data.attendanceStatus) {
            case 0:
                workingEndMenu.classList.add('unselectable_menu')
                workingEndMenu.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                break
            case 1:
                workingStartMenu.classList.add('unselectable_menu')
                workingStartMenu.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                break
            case 2:
                workingStartMenu.classList.add('unselectable_menu')
                workingStartMenu.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                workingEndMenu.classList.add('unselectable_menu')
                workingEndMenu.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                break
        }
    } catch (error: unknown) {
        if (error instanceof Error) {
            console.error(error.message)
        }
    }
})()
