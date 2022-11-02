'use strict'

window.onload = () => {
    async function changeMenuStateAccAttendanceStatus() {
        try {
            await window
                .fetch('api_attendance_record', {
                    method: 'GET'
                })
                .then((res) => {
                    if (!res.ok) {
                        console.error(res.status, 'server error')
                    }
                    return res.json()
                })
                .then((result) => {
                    if (result.attendanceStatus == 1) {
                        const workingStartMenu = document.getElementById('workingStart')

                        workingStartMenu.classList.add('unselectable_menu')
                        workingStartMenu.firstElementChild.href = 'javascript:void(0)'
                    } else if (result.attendanceStatus == 2) {
                        const workingStartMenu = document.getElementById('workingStart')
                        const workingEndMenu = document.getElementById('workingEnd')

                        workingStartMenu.classList.add('unselectable_menu')
                        workingStartMenu.firstElementChild.href = 'javascript:void(0)'
                        workingStartMenu.classList.add('unselectable_menu')
                        workingEndMenu.firstElementChild.href = 'javascript:void(0)'
                    }
                    return result
                })
                .catch((error) => {
                    console.error(error)
                })
        } catch (error) {
            console.error(error)
        }
    }
    changeMenuStateAccAttendanceStatus()
}
