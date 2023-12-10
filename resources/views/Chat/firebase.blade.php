<script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-analytics.js";
    import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-messaging.js";

    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyAdoPaDTTM6O218Qi0CbR9eZvCQdlmYfq4",
      authDomain: "missplaced-1780b.firebaseapp.com",
      projectId: "missplaced-1780b",
      storageBucket: "missplaced-1780b.appspot.com",
      messagingSenderId: "790746922975",
      appId: "1:790746922975:web:a059d3a40133f3d34f4e6a",
      measurementId: "G-YX9N7VPPV5"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);

    // Retrieve Firebase Messaging object.
    const messaging = getMessaging();
'@if(Auth::User())'
    function sendTokenToServer(fcm_token) {
      const user_id = '{{auth()->user()->id}}';
      axios.post('/save-token', { fcm_token, user_id })
        .then(res => {
          console.log(res);
        });
    }

    function retrieveToken() {
      getToken(messaging).then((currentToken) => {
        if (currentToken) {
          sendTokenToServer(currentToken);
        } else {
          alert('You should allow notifications!');
        }
      }).catch((err) => {
        console.log(err.message);
      });
    }

    retrieveToken();

    messaging.onTokenRefresh(() => {
      retrieveToken();
    });

    messaging.onMessage((payload) => {
      console.log('Message received');
      console.log(payload);
      location.reload();
    });
'@endif'
  </script>
