(() => {
    const navigation = document.getElementById('site-navigation');
    const toggleButton = document.querySelector('.menu-toggle');

    if (navigation && toggleButton) {
        toggleButton.addEventListener('click', () => {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            const nextState = String(!isExpanded);
            toggleButton.setAttribute('aria-expanded', nextState);
            navigation.dataset.open = nextState;
        });
    }

    const visitForm = document.getElementById('visit-form');
    if (!visitForm) {
        return;
    }

    const dateInput = document.getElementById('visit-date');
    const quantityInput = document.getElementById('visit-quantity');
    const feedback = document.getElementById('visit-feedback');

    const maskDateValue = (rawValue) => {
        const digits = rawValue.replace(/\D/g, '').slice(0, 8);
        const parts = [];
        if (digits.length > 0) {
            parts.push(digits.slice(0, 2));
        }
        if (digits.length > 2) {
            parts.push(digits.slice(2, 4));
        }
        if (digits.length > 4) {
            parts.push(digits.slice(4, 8));
        }
        return parts.join('/');
    };

    const clearFeedback = () => {
        if (!feedback) {
            return;
        }
        feedback.hidden = true;
        feedback.textContent = '';
        feedback.classList.remove('form-feedback--error', 'form-feedback--success');
    };

    const showFeedback = (message, isSuccess) => {
        if (!feedback) {
            return;
        }
        feedback.hidden = false;
        feedback.textContent = message;
        feedback.classList.toggle('form-feedback--success', isSuccess);
        feedback.classList.toggle('form-feedback--error', !isSuccess);
    };

    const markFieldValidity = (field, isValid) => {
        if (!field) {
            return;
        }
        if (isValid) {
            field.removeAttribute('aria-invalid');
        } else {
            field.setAttribute('aria-invalid', 'true');
        }
    };

    const validateDate = (value) => {
        if (!value) {
            return { valid: false, message: 'Informe a data da visita.' };
        }
        if (!/^\d{2}\/\d{2}\/\d{4}$/.test(value)) {
            return { valid: false, message: 'Use o formato dia/mês/ano.' };
        }
        const [day, month, year] = value.split('/').map(Number);
        const parsedDate = new Date(year, month - 1, day);
        if (
            Number.isNaN(parsedDate.getTime()) ||
            parsedDate.getFullYear() !== year ||
            parsedDate.getMonth() !== month - 1 ||
            parsedDate.getDate() !== day
        ) {
            return { valid: false, message: 'Informe uma data válida.' };
        }
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (parsedDate < today) {
            return { valid: false, message: 'Escolha uma data a partir de hoje.' };
        }
        return { valid: true };
    };

    const validateQuantity = (value) => {
        if (value === '' || value === null) {
            return { valid: false, message: 'Informe a quantidade de visitantes.' };
        }
        const numericValue = Number(value);
        if (!Number.isInteger(numericValue)) {
            return { valid: false, message: 'Use apenas números inteiros.' };
        }
        if (numericValue < 1) {
            return { valid: false, message: 'A quantidade mínima é de 1 visitante.' };
        }
        if (numericValue > 10) {
            return { valid: false, message: 'A quantidade máxima é de 10 visitantes por agendamento.' };
        }
        return { valid: true };
    };

    if (dateInput) {
        dateInput.addEventListener('input', () => {
            dateInput.value = maskDateValue(dateInput.value);
            markFieldValidity(dateInput, true);
            clearFeedback();
        });
    }

    if (quantityInput) {
        quantityInput.addEventListener('input', () => {
            markFieldValidity(quantityInput, true);
            clearFeedback();
        });
    }

    visitForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const messages = [];
        let firstInvalidField = null;

        if (dateInput) {
            const result = validateDate(dateInput.value);
            if (!result.valid) {
                messages.push(result.message);
                markFieldValidity(dateInput, false);
                if (!firstInvalidField) {
                    firstInvalidField = dateInput;
                }
            } else {
                markFieldValidity(dateInput, true);
            }
        }

        if (quantityInput) {
            const result = validateQuantity(quantityInput.value);
            if (!result.valid) {
                messages.push(result.message);
                markFieldValidity(quantityInput, false);
                if (!firstInvalidField) {
                    firstInvalidField = quantityInput;
                }
            } else {
                markFieldValidity(quantityInput, true);
            }
        }

        if (messages.length > 0) {
            showFeedback(messages.join(' '), false);
            firstInvalidField?.focus();
            return;
        }

        visitForm.reset();
        showFeedback('Sua visita foi registrada! Em breve enviaremos a confirmação.', true);
        if (dateInput) {
            dateInput.value = '';
            markFieldValidity(dateInput, true);
        }
        if (quantityInput) {
            quantityInput.value = '1';
            markFieldValidity(quantityInput, true);
        }
    });

    visitForm.addEventListener('reset', () => {
        clearFeedback();
        markFieldValidity(dateInput, true);
        markFieldValidity(quantityInput, true);
    });
})();
