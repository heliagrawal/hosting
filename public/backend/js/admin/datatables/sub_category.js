(function($) {
    "use strict";
    $(document).ready(function () {
        $('#SubCategoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            columns: [
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'SubCategory_Name',
                    name: 'SubCategory_Name'
                },
                {
                    data: 'SubCategory_Slug',
                    name: 'SubCategory_Slug'
                },
                {
                    data: 'Description',
                    name: 'Description'
                },
                {
                    data: 'SubCategory_Icon',
                    name: 'SubCategory_Icon'
                },
                {
                    data: 'Status',
                    name: 'Status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });
    });
})(jQuery)
