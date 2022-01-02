 /** @type {Array<Element>} dropdownElements */
const dropdownElements = [].slice.call(document.querySelectorAll(".dropdown-toggle"));
 /** @type {Array<bootstrap.Dropdown>} dropdowns */
const dropdowns = [];

dropdownElements.forEach(function (element) {
    dropdowns[element.id] = new bootstrap.Dropdown(element);
});

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
        launchErrorAlert("There was an error editing the status: " + error.response.data.message ?? "" + "<br>" + error.response.data["errors"] ?? "");
    });

    event.preventDefault();
}
