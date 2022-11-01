// TODO 場所変える
const historyBackLink = document.getElementById('historyBackLink')
historyBackLink.addEventListener(
    'click',
    function () {
        window.history.back()
    },
    false
)
