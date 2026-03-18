<script src="{{ asset('js/modal.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/chart.js') }}"></script>
<script>
 window.appConfig = {
        success: @json(session('success')),
        error: @json(session('error')),
        errors: @json($errors->all())
    };
window.vapidPublicKey = "{{ config('webpush.vapid.public_key') }}";

if ('serviceWorker' in navigator) {

navigator.serviceWorker.register('/service-worker.js').then(function(reg){

reg.pushManager.getSubscription().then(function(subscription){

if(subscription === null){

const convertedKey = urlBase64ToUint8Array(window.vapidPublicKey);

reg.pushManager.subscribe({
userVisibleOnly: true,
applicationServerKey: convertedKey
}).then(function(newSub){

sendSubscriptionToServer(newSub);

});

}else{

sendSubscriptionToServer(subscription);

}

});

});

}

function sendSubscriptionToServer(subscription){

fetch('/subscribe',{
method:'POST',
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':'{{ csrf_token() }}'
},
body:JSON.stringify(subscription)
});

}

function urlBase64ToUint8Array(base64String){

const padding = '='.repeat((4 - base64String.length % 4) % 4);
const base64 = (base64String + padding)
.replace(/-/g, '+')
.replace(/_/g, '/');

const rawData = window.atob(base64);
const outputArray = new Uint8Array(rawData.length);

for (let i = 0; i < rawData.length; ++i) {
outputArray[i] = rawData.charCodeAt(i);
}

return outputArray;

}
</script>

</body>
</html>