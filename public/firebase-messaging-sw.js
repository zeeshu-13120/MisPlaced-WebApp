importScripts(
    "https://www.gstatic.com/firebasejs/10.4.0/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/10.4.0/firebase-messaging-compat.js"
);

const firebaseConfig = {
    apiKey: "AIzaSyAdoPaDTTM6O218Qi0CbR9eZvCQdlmYfq4",
    authDomain: "missplaced-1780b.firebaseapp.com",
    projectId: "missplaced-1780b",
    storageBucket: "missplaced-1780b.appspot.com",
    messagingSenderId: "790746922975",
    appId: "1:790746922975:web:a059d3a40133f3d34f4e6a",
    measurementId: "G-YX9N7VPPV5",
};

firebase.initializeApp(firebaseConfig);

// Retrieve firebase messaging
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    console.log("Received background message ", payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
