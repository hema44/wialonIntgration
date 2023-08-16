import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import { Room } from 'livekit-client';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// window.Vue = require('vue');
//
// Vue.component('slider', require('./components/HomeSlider.vue').default);
//
// const app = new Vue({
//     el: '#app'
// });

const wsURL = "https://voicecheck-jvy2vtw3.livekit.cloud"
const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJpYnJhaGVtIiwianRpIjoiaWJyYWhlbSIsImV4cCI6MTY5MjIxMTkzMCwibmJmIjoxNjkyMTk3NTMwLCJpYXQiOjE2OTIxOTc1MzAsImlzcyI6IkFQSWpuRmFYaExRWVpVTSIsInZpZGVvIjp7InJvb20iOiJpYnJhaGVtIn19._-_PrmIHGkW7Zck6MswKUmu_CgTkkAxW_25ynlpDDLI'

const room = new Room();
await room.connect(wsURL, token);
console.log('connected to room', room.name);

// publish local camera and mic tracks
await room.localParticipant.enableCameraAndMicrophone();
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
