importScripts('https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/6.3.4/firebase-messaging.js');

// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyBIdoXxWrkmgFSILxa3KF9JSt_hAWk2Vtg",
    authDomain: "ui-siptla.firebaseapp.com",
    databaseURL: "https://ui-siptla.firebaseio.com",
    projectId: "ui-siptla",
    storageBucket: "ui-siptla.appspot.com",
    messagingSenderId: "824351596044",
    appId: "1:824351596044:web:34175e5e72f16eb3855829",
    measurementId: "G-NWETZ3K68D"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
firebase.analytics();

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});