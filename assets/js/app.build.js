import {Tooltip, Popover} from 'bootstrap';

document.addEventListener("DOMContentLoaded", () => {
    let tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));

    let popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].map(popoverTriggerEl => new Popover(popoverTriggerEl));
});
