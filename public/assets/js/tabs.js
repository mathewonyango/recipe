document.addEventListener('DOMContentLoaded', function() {
    $('#connectionTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});
