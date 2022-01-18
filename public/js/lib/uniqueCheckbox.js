function setupUniqueCheckboxes(formId, onChange) {
    const formTargets = [...document.querySelectorAll(`#${formId} input[type='checkbox']`)];
    formTargets.forEach((elem) => {
        elem.addEventListener('change', (e) => {
            if (e.currentTarget.checked) {
                const group = e.currentTarget.getAttribute("group");
                const id = e.currentTarget.id;
                
                const targets = [...document
                    .querySelectorAll(`#${formId} input[unique][type='checkbox'][group=${group}]:not([id=${id}])`)];
                
                targets.forEach((elem) => elem.checked = false);
            }

            onChange && onChange(e);
        })
    });
}
