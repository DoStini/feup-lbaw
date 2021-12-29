function setupUniqueCheckboxes(formId, onChange) {
    const formTargets = $(`#${formId} input[unique][type='checkbox']`).toArray();
    
    formTargets.forEach((elem) => {
        elem.addEventListener('change', (e) => {
            if (e.currentTarget.checked) {
                const group = e.currentTarget.getAttribute("group");
                const id = e.currentTarget.id;
    
                const targets = $(`#${formId} input[unique][type='checkbox'][id!=${id}][group=${group}]`).toArray();
                
                targets.forEach((elem) => elem.checked = false);
            }

            onChange && onChange(e);
        })
    });

}
