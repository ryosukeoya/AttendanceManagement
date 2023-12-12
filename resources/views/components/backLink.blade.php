<a id="backLink" class="text-blue-800">
    < 戻る</a>

{{-- <script>
    const backLink = document.getElementById('backLink')

    backLink.addEventListener('click', window.history.back, false)
</script> --}}

<script>
    const backLink = document.getElementById('backLink')

    backLink.addEventListener('click', () => window.history.back(), false)
 </script>
