function jsonBodyPost(path, data) {
    return window.axios.post(
        path,
        data,
        {
            headers: {
                'Content-Type': 'application/json'
            }
        }
    );
}

function get(path) {
    return window.axios.get(path);
}