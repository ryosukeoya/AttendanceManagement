import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import checker from 'vite-plugin-checker'

export default defineConfig({
    plugins: [
        laravel({
            // TODO
            input: [
                'resources/css/app.css',
                'resources/ts/app.ts',
                'resources/ts/backLink.ts',
                'resources/ts/calendar.ts',
                'resources/ts/menuState.ts',
                'resources/ts/modal.ts'
            ],
            refresh: true
        }),
        checker({ typescript: true })
    ]
})
