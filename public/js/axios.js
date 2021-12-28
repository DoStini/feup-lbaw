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