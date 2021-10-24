require('./bootstrap');

require('alpinejs');

$(function () {
    $(document).on('submit', '#delete-order-form', function () {
        return confirm('Are you sure?');
    })
})
