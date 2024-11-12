
export function foundEmpty(form) {
    for (const [key, value] of form.entries()) {
        if (value === "") {
            return true;
        } else {
            return false
        }
    }
};