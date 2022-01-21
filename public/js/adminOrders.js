let dropdownElements;
let dropdowns;

function updateAny(event, id) {
    const formData = new FormData(document.getElementById(`edit-order-status-form-${id}`));

    const dropdownOrderStatus = dropdowns[`dropdown-menu-order-status-btn-${id}`];
    dropdownOrderStatus.hide();

    update(id, formData.get('status'));

    event.preventDefault();
}

function update(id, status) {
    let body = null;

    if (status != null)
        body = {'status' : status};
    
    jsonBodyPost(`/api/orders/${id}/status`, body)
    .then((response) => {
        launchSuccessAlert(`Order no. ${id} updated successfully to ` + response.data["updated-order"].status + "!");
    })
    .catch((error) => {
        launchErrorAlert("Couldn't update the status: " + error.response.data.message ?? "" + "<br>" + error.response.data["errors"] ?? "");
    });
    
    table.ajax.reload();
}
