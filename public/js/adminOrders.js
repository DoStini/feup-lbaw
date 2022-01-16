let dropdownElements;
let dropdowns;

function sendOrderStatus(event, id) {
    let requestURL = "/api/orders/";
    const formData = new FormData(document.getElementById(`edit-order-status-form-${id}`));

    requestURL = requestURL.concat(id, "/status");
    const dropdownOrderStatus = dropdowns[`dropdown-menu-order-status-btn-${id}`];
    dropdownOrderStatus.hide();

    jsonBodyPost(requestURL,  {'status' : formData.get('status')})
    .then((response) => {
        launchSuccessAlert("Order Updated Successfully!");
    })
    .catch((error) => {
        launchErrorAlert("Couldn't edit the status: " + error.response.data.message ?? "" + "<br>" + error.response.data["errors"] ?? "");
    });

    table.ajax.reload();

    event.preventDefault();
}
