function clearForm(form) {
    form.reset();
}

function createUser(user) {
    const html = `
        <td class="text-center">${user.id}</td>
        <td class="text-center">TBD</td>
        <td class="text-center">${user.name}</td>
        <td class="text-center">${user.email}</td>
        <td class="text-center">${user.phone_number ?? '-'}</td>
        <td class="text-center">${user.nif ?? '-'}</td>
        <td class="text-center">${user.newsletter_subcribed ? 'Yes' : 'No'}</td>
        <td>
            <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                <a class="bi bi-info-circle-fill icon-click" href="/users/${user.id}" data-bs-toggle="tooltip" title="Go to User Page"></a>
                <a class="bi bi-pencil-square icon-click px-1" href="/users/${user.id}/private/" data-bs-toggle="tooltip" title="Edit User Info"></a>
            </div>
        </td>`;

    // <a class="bi bi-dash-circle-fill icon-click" data-bs-toggle="tooltip" title="Ban User"></a>

    const element = document.createElement('tr');
    element.style = "visibility: visible";
    element.innerHTML = html;

    const container = document.getElementById('user-area');
    container.appendChild(element);

    return element;
}

function clearUsers() {
    const container = document.getElementById('user-area');

    container.innerHTML = "";
}

function insertUsers(data) {
    const container = document.getElementById('user-area');

    data.query.forEach((target, idx) =>
        container.append(createUser(target)));
}


function setUsers(data) {
    clearUsers();
    insertUsers(data, true);
}

function handleSearchUsers(response) {

    const parsedResponse = JSON.parse(response.target.response);

    if (response.target.status !== 200) return;

    setUsers(parsedResponse);
}

function setupSearchListeners() {
    const form = document.getElementById('user-dashboard-form');

    if(!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        sendSearchUsersRequest(form, handleSearchUsers);
    });

    // window.onload = () => {
    //     clearForm(form);
    //     sendSearchUsersRequest(form, handleSearchUsers);
    // }


}

function sendSearchUsersRequest(form, callback) {
    sendAjaxQueryRequest('get', '/api/users', query, callback);

    clearForm(form);
}


setupSearchListeners();
