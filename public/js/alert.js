const alertPlaceholder = document.getElementById('alert-placeholder');

function launchSuccessAlert(message) {
    launchAlert(message, 'success')
}

function launchErrorAlert(message) {
    launchAlert(message, 'danger')
}

function launchAlert(message, type) {
    var wrapper = document.createElement('div')
    wrapper.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
  
    alertPlaceholder.append(wrapper);
}