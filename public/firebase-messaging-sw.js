
//
// importScripts('https://www.gstatic.com/firebasejs/4.6.2/firebase-app.js');
// importScripts('https://www.gstatic.com/firebasejs/4.6.2/firebase-messaging.js');
//
// const firebaseConfig = {
//     apiKey: "AIzaSyDtLb6c2XGyDX1NZ5lwdmWi6p37d-zGON8",
//     authDomain: "mytestfcm-7404d.firebaseapp.com",
//     databaseURL: "https://mytestfcm-7404d-default-rtdb.firebaseio.com",
//     projectId: "mytestfcm-7404d",
//     storageBucket: "mytestfcm-7404d.appspot.com",
//     messagingSenderId: "558625884661",
//     appId: "1:558625884661:web:b79b440e4e33b04402b8bc",
//     measurementId: "G-PX02BYE8XP"
// };
// firebase.initializeApp(config);
//
// const messaging = firebase.messaging();
//
// const messaging = getMessaging(app);
// // //
// // //
// // getToken(messaging, { vapidKey: "BAYq9JmlgTtRwqT6mJOIFOAi1X5Nb9nXjQCAvkAHkbGMD30-muTUg4_frXf9aG-n-giQFHUi_s5Udt2xy3LOMmU" }).then((currentToken) => {
// //     if (currentToken) {
// //         // Send the token to your server and update the UI if necessary
// //         // ...
// //         console.log(currentToken);
// //     } else {
// //         // Show permission request UI
// //         console.log('No registration token available. Request permission to generate one.');
// //         // ...
// //     }
// // }).catch((err) => {
// //     console.log('An error occurred while retrieving token. ', err);
// //     // ...
// // });
// //
// // console.log(onMessage);
//
// messaging.onBackgroundMessage((payload) => {
//     console.log(
//         '[firebase-messaging-sw.js] Received background message ',
//         payload,
//     )
//     // Customize notification here
//     const notificationTitle = 'Background Message Title'
//     const notificationOptions = {
//         body: 'Background Message body.',
//         icon: '/firebase-logo.png',
//     }
// })