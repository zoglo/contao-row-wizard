import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['body', 'row', 'copy', 'delete'];
    static values = {
        min: Number,
        max: Number,
    };

    connect() {
        this.#updatePermissions();
    }

    rowTargetConnected() {
        this.updateSorting();
    }

    rowTargetDisconnected() {
        this.updateSorting();
    }

    copy(event) {
        if (!this.#copyAllowed()) {
            return;
        }

        const row = this._getRow(event);
        const previous = row.previousElementSibling;

        // Cause Choices and similar controllers to be disconnected
        row.remove();

        // Wait until Stimulus controllers are disconnected
        queueMicrotask(() => {
            const newRow = row.cloneNode(true);

            // BC for MooChosen / Contao < 5.5
            if (typeof Chosen !== "undefined") {
                const selects = newRow.querySelectorAll('select.tl_select.tl_chosen');

                for (const select of selects) {
                    select.removeAttribute('id');
                    select.nextSibling.remove();
                    new Chosen(select);
                }
            }

            // Re-insert the previous and new row
            if (previous) {
                previous.after(row, newRow);
            } else {
                this.bodyTarget.prepend(row, newRow);
            }

            this._focus(newRow);
            this.#updatePermissions();
        });
    }

    delete(event) {
        if (!this.#deleteAllowed()) {
            return;
        }

        const row = this._getRow(event);

        if (this.bodyTarget.children.length > 1) {
            this._focus(row.nextElementSibling) ||
                this._focus(row.previousElementSibling) ||
                this._focus(this.bodyTarget);

            row.remove();
        } else {
            this._resetInputs(row);
            this._focus(row);
        }

        this.#updatePermissions();
    }

    updateSorting() {
        Array.from(this.bodyTarget.children).forEach((tr, i) => {
            for (const el of tr.querySelectorAll('label, input, select, textarea')) {
                if (el.name) {
                    el.name = el.name.replace(/\[[0-9]+]/g, `[${i}]`);
                }

                if (el.id) {
                    el.id = el.id.replace(/_[0-9]+(_|$)/g, `_${i}$1`);
                }

                if (el.getAttribute('for')) {
                    el.setAttribute('for', el.getAttribute('for').replace(/_[0-9]+(_|$)/g, `_${i}$1`));
                }
            }
        });
    }

    _getRow(event) {
        return event.target.closest(`*[data-${this.identifier}-target="row"]`);
    }

    _resetInputs(row) {
        for (const input of row.querySelectorAll('input')) {
            input.value = '';
        }

        for (const select of row.querySelectorAll('select')) {
            select.value = select.children[0].value;
        }
    }

    _focus(el) {
        if (!el) {
            return false;
        }

        el.querySelector('input, select:not(.choices__input), .tl_select.choices')?.focus();

        return true;
    }

    #deleteAllowed() {
        return !(this.hasMinValue && this.bodyTarget.children.length === this.minValue);
    }

    #copyAllowed() {
        return !(this.hasMaxValue && this.bodyTarget.children.length === this.maxValue);
    }

    #updatePermissions() {
        if (this.hasMinValue) {
            const enable = this.#deleteAllowed();

            for (const el of this.deleteTargets) {
                if (enable) {
                    el.removeAttribute('disabled');
                } else {
                    el.disabled = true;
                }
            }
        }

        if (this.hasMaxValue) {
            const enable = this.#copyAllowed();

            for (const el of this.copyTargets) {
                if (enable) {
                    el.removeAttribute('disabled');
                } else {
                    el.disabled = true;
                }
            }
        }
    }
}
