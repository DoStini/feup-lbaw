function route(route, data) {
    history.pushState(data, document.title, "");
    window.location.assign(`/${route}`);
}
