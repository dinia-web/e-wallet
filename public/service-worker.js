self.addEventListener('push', function(event) {

    let data = {};

    if(event.data){
        data = event.data.json();
    }

    const title = data.title || "Notifikasi";

    const options = {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        data: data.data
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );

});

self.addEventListener('notificationclick', function(event) {

    event.notification.close();

    const url = event.notification.data?.url || "/";

    event.waitUntil(
        clients.openWindow(url)
    );

});