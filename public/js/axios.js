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

function deleteRequest(path) {
    return window.axios.delete(
        path,
    );
}

function get(path) {
    return window.axios.get(path);
}

function getQuery(path, query) {
    return window.axios.get(path, {params : query});
}
