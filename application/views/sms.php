<html>
<head>
	<title></title>
</head>
<body>
<h1>Ami</h1>
</body>
</html>
<script src="https://www.gstatic.com/firebasejs/5.3.0/firebase.js"></script>
<script>
 var config = {
    apiKey: "AIzaSyDJXl4mnAqqi-CLeuSXrxazJ67z6Oa8c_w",
    authDomain: "lupirka-912be.firebaseapp.com",
    databaseURL: "https://lupirka-912be.firebaseio.com",
    projectId: "lupirka-912be",
    storageBucket: "lupirka-912be.appspot.com",
    messagingSenderId: "261715901852"
  };
  firebase.initializeApp(config);
        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                // MsgElem.innerHTML = "Notification permission granted." 
                console.log("Notification permission granted.");

                // get the token in the form of promise
                console.log("Cek Token -- "+messaging.getToken()) 
                return messaging.getToken()

            })
            .then(function(token) {
                // TokenElem.innerHTML = "token is : " + token
                 console.log("Token -- "+token) 
                
            })
            .catch(function (err) {
                // ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission.", err);
            });

        messaging.onMessage(function(payload) {
            console.log("Message received. ", payload);
            // NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload) 
        });

        messaging.onTokenRefresh(function() {
    messaging.getToken()
    .then(function(refreshedToken) {
        console.log('Token refreshed.');
  // send new token info to server here
    })
    .catch(function(err) {
        console.log('Unable to retrieve refreshed token ', err);
    });
});

        if ('serviceWorker' in navigator) {
  // Register a service worker hosted at the root of the
  // site using a more restrictive scope.
  navigator.serviceWorker.register('/firebase-messaging-sw.js', {scope: './'}).then(function(registration) {
    console.log('Service worker registration succeeded:', registration);
  }, /*catch*/ function(error) {
    console.log('Service worker registration failed:', error);
  });
} else {
  console.log('Service workers are not supported.');
}




</script>