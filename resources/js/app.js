import './bootstrap'

import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

// TODO　場所変える
window.onload = () => {
    try {
        async function getRecord() {
            const response = await window
                .fetch('api_attendance_record', {
                    method: 'GET'
                })
                .then((res) => {
                    if (!res.ok) {
                        console.error(res.status, 'server error')
                    }
                    return res.json()
                })
                .catch((error) => {
                    console.error(error)
                })
            if (response.hoge) {
                const menuItems = document.querySelectorAll('[data = menu-item]')
                // TODO gray, menuItmes[0]
                menuItems[0].classList.add('gray')
                // menuItems[0].firstElementChild.href = "javascript:void(0)"
            }
        }
        getRecord()
    } catch (error) {
        console.error(error)
    }
}

// TODO 場所変える
const historyBackLink = document.getElementById('historyBackLink')
historyBackLink.addEventListener(
    'click',
    function () {
        window.history.back()
    },
    false
)
