// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

const pusher = new Pusher('4c7db76f6f7fd6381f0e', {
    cluster: 'eu'
});

const channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
    alert(JSON.stringify(data));
});
