// emulate cordova for testing on Chrome
//cordova.navigator.notification.alert("message", callback, "title", "buttons");
var simulador = {
        navigator: {
            connection: function () {
                if (navigator.connection) {
                    return navigator.connection;
                } else {
                    console.log('navigator.connection');
                    return {
                        type: "WIFI", // Avoids errors on Chrome
                    };
                }
            },
            isConnected : function(){
                if(navigator.connection){
                    return navigator.onLine;
                }else{
                    return navigator.onLine;
                }
            },
            camera: {
                options : function() {
                    if(navigator.camera){
                        return {
                            destinationType: Camera.DestinationType.DATA_URI,
                            correctOrientation: false,
                            encodingType: Camera.EncodingType.JPEG,
                            sourceType: Camera.PictureSourceType.PHOTOLIBRARY
                        };
                    }else{
                        return {
                            quality: "50",
                            destinationType: "Camera.DestinationType.FILE_URI",
                            sourceType: "Camera.PictureSourceType.PHOTOLIBRARY"
                        };
                    }
                },
                getPicture : function(onSuccess, onFail, cameraOptions){
                  if(navigator.camera){
                        navigator.camera.getPicture( onSuccess, onFail, cameraOptions );
                    }else{
                        console.log("navigator.camera.getPicture");
                    }
                }
            },
            notification: {
                vibrate: function (a) {
                    if (navigator.notification && navigator.notification.vibrate) {
                        navigator.notification.vibrate(a);
                    } else {
                        console.log("navigator.notification.vibrate");
                    }
                },
                alert: function (message, callback, title, buttonName) {
                    if (navigator.notification && navigator.notification.alert) {
                        navigator.notification.alert(message, callback, title, buttonName);
                    } else {
                        console.log("navigator.notification.alert");
                        alert(message);
                    }
                },
                confirm: function (message, callback, title, buttonlabels) {
                    if (navigator.notification && navigator.notification.confirm) {
                        navigator.notification.confirm(message, callback, title, buttonlabels);
                    } else {
                        console.log("navigator.notification.confirm");
                        return confirm(message);
                    }
                }
            },
            splashscreen: {
                hide: function () {
                    if (navigator.splashscreen) {
                        navigator.splashscreen.hide();
                    } else {
                        console.log('navigator.splashscreen.hide');
                    }
                }
            }
        }
    };