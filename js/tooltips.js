/**
 * Initializes Bootstrap tooltips and handles dynamic content
 * Sets up tooltips for both initial page load and dynamically loaded content
 */
document.addEventListener('DOMContentLoaded', function() {
    /**
     * Initialize tooltips on all elements with data-bs-toggle="tooltip" attribute
     * Creates array of tooltip elements and initializes Bootstrap tooltip on each
     */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    /**
     * Reinitialize tooltips when Bootstrap modals are shown
     * Ensures tooltips work on dynamically loaded modal content
     */
    document.addEventListener('shown.bs.modal', function() {
        var modalTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var modalTooltipList = modalTooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
}); 