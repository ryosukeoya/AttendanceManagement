type AttendanceStatusApi = { attendanceStatus: number }

const changeMenuStatus = (async () => {
    try {
        const res = fetch('api_attendance_record/me/today_status', { method: 'GET' })
        const data: AttendanceStatusApi = await res.then((res: Response) => {
            if (!res.ok) console.error(res.status, 'server error')
            return res.json()
        })
        const startMenu = document.getElementById('workingStart')!
        const endMenu = document.getElementById('workingEnd')!

        switch (data.attendanceStatus) {
            case 0:
                endMenu.classList.add('unselectable_menu')
                endMenu.firstElementChild!.setAttribute('href', 'javascript:void(0)')
                break
            case 1:
                startMenu.classList.add('unselectable_menu')
                startMenu.firstElementChild!.setAttribute('href', 'javascript:void(0)')
                break
            case 2:
                startMenu.classList.add('unselectable_menu')
                startMenu.firstElementChild!.setAttribute('href', 'javascript:void(0)')
                endMenu.classList.add('unselectable_menu')
                endMenu.firstElementChild!.setAttribute('href', 'javascript:void(0)')
                break
        }
    } catch (error: unknown) {
        if (error instanceof Error) console.error(error.message)
    }
})()
