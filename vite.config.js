import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import checker from 'vite-plugin-checker'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/ts/app.ts'
            ],
            refresh: true
        }),
        checker({ typescript: true })
    ]
})
