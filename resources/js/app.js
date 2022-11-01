import './bootstrap'

import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

// TODO　場所変える
window.onload = () => {
    async function getRecord() {
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

                    // TODO Refactor
                    if (result.attendanceStatus == 1) {
                        const workingStartMenu = document.getElementById('workingStart')

                        workingStartMenu.classList.add('gray')
                        workingStartMenu.classList.add('pointer_events_none')
                        workingStartMenu.firstElementChild.href = 'javascript:void(0)'
                    } else if (result.attendanceStatus == 2) {
                        const workingStartMenu = document.getElementById('workingStart')
                        const workingEndMenu = document.getElementById('workingEnd')

                        workingStartMenu.classList.add('gray')
                        workingStartMenu.classList.add('pointer_events_none')
                        workingStartMenu.firstElementChild.href = 'javascript:void(0)'
                        workingEndMenu.classList.add('gray')
                        workingEndMenu.classList.add('pointer_events_none')
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
    getRecord()
}
