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

function formDataPost(path, data) {
    return window.axios.post
    (
        path,
        data,
        {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }
    )
}

function jsonBodyDelete(path, data) {
    return window.axios.delete(
        path,
        {
            data,
            headers: {
                'Content-Type': 'application/json'
            }
        }
    );
}

function get(path) {
    return window.axios.get(path);
}