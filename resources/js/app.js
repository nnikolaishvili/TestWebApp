require('./bootstrap');

require('alpinejs');

$(function () {
    $(document).on('submit', '#cancel-order-form', function () {
        return confirm('Are you sure?');
    })

    $(document).on('submit', '#delete-product-form', function () {
        return confirm('Are you sure?');
    })

    $(document).on('change', '#image_file', function () {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (event) {
                $('#image-preview > img').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
})
