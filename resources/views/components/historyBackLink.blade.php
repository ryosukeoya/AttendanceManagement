<a id="historyBackLink" class="text-blue-800">
    < 戻る</a>

{{-- FIXME --}}
<script>
    // TODO 場所変える
    const historyBackLink = document.getElementById('historyBackLink')
    historyBackLink.addEventListener('click',function () {
        window.history.back()
    },false)
 </script>