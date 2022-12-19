type AttendanceStatus = number

type AttendanceStatusApi = { attendanceStatus: AttendanceStatus }

window.onload = () => {
    async function changeMenuStateAccAttendanceStatus() {
        await window
            .fetch('api_attendance_record/me/today_status', {
                method: 'GET'
            })
            .then((res: Response) => {
                if (!res.ok) {
                    console.error(res.status, 'server error')
                }
                return res.json()
            })
            .then((result: AttendanceStatusApi) => {
                if (result.attendanceStatus == 0) {
                    const workingEndMenu = document.getElementById('workingEnd')

                    if (workingEndMenu) {
                        workingEndMenu.classList.add('unselectable_menu')
                        workingEndMenu.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                    }
                } else if (result.attendanceStatus == 1) {
                    const workingStartMenu = document.getElementById('workingStart')

                    if (workingStartMenu) {
                        workingStartMenu?.classList.add('unselectable_menu')
                        workingStartMenu?.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                    }
                } else if (result.attendanceStatus == 2) {
                    const workingStartMenu = document.getElementById('workingStart')
                    const workingEndMenu = document.getElementById('workingEnd')

                    if (workingStartMenu && workingEndMenu) {
                        workingStartMenu?.classList.add('unselectable_menu')
                        workingStartMenu?.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                        workingEndMenu?.classList.add('unselectable_menu')
                        workingEndMenu?.firstElementChild?.setAttribute('href', 'javascript:void(0)')
                    }
                }
                return result
            })
            .catch((error: unknown) => {
                if (error instanceof Error) {
                    console.error(error.message)
                }
            })
    }
    changeMenuStateAccAttendanceStatus()
}
