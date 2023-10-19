<script type="text/javascript">
let modal = $('#modalkelas');
let modal_title = $('#modalkelaslabel');
let title_toastr = 'Kelas Information';
$(document).ready(function() {

    let i = 1;

    let table = new DataTable('#table-example3', {
        'processing': true,
        'serverSide': true,
        ordering: true,
        order: [],
        'dom': "<'row'<'col-sm-12 col-md-4 d-flex align-items-center'B><'col-sm-12 col-md-4 'l><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: {
            'buttons': [{
                    text: '<i class="fas fa-plus plus"></i> Add',
                    width: '3%',
                    action: function(e, dt, node, config) {
                        tambah();
                    },
                    attr: {
                        class: 'btn btn-sm mr-1 btn-outline-primary',
                    }
                }, {
                    text: 'Delete',
                    action: function(e, dt, node, config) {
                        bulk_delete();
                    },
                    attr: {
                        class: 'btn btn-sm mr-1 btn-outline-danger bulk-delete',
                        disabled: 'disabled'

                    }
                },

                {
                    text: '<i class="fas fa-sync-alt sync-alt"></i> Reload',
                    action: function(e, dt, node, config) {
                        reload();
                    },
                    attr: {
                        class: 'btn btn-sm mr-1 btn-outline-secondary',
                    }
                },
                {
                    text: '<i class="fas fa-fw fa-print"></i> Example Format',
                    action: function(e, dt, node, config) {
                        var $a = $("<a>");
                        $a.attr("href", '<?= site_url('admin/export/kelas'); ?>');
                        $("body").append($a);
                        $a[0].click();
                        $a.remove();
                    },
                    attr: {
                        class: 'btn mr-1 btn-info btn-sm exampleformat',
                        disabled: 'disabled'
                    }
                }


            ],
        },
        'reponsive': true,

        'ajax': {
            'url': "<?= site_url('admin/datatables/kelas/ajax_list'); ?>",
            'type': 'post'
        },
        'deferRender': true,
        'columnDefs': [{
                'targets': 0,
                'data': null,
                'width': '3%',
                'searchable': false,
                'orderable': false,
            },
            {
                'targets': 1,
                'data': null,
                'searchable': false,
                'orderable': false,
            },
            {
                'targets': 2,
                'data': 'kelas'
            },
            {
                'targets': 3,
                'data': 'jurusan'
            },
            {
                'targets': 4,
                'data': null,
                'defaultContent': '<a class="btn btn-sm btn-outline-danger mr-1 delete"">Delete</a> <a class="btn btn-sm btn-outline-success mr-1 update"">Update</a>',
                'searchable': false,
                'orderable': false,

            }
        ]
    });




    table.on('click', '.delete', function(e) {
        let data = table.row(e.target.closest('tr')).data();

        Swal.fire({
            title: 'Apakah ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '<?= site_url('admin/datatables/kelas/delete'); ?>',
                    data: {
                        id: data.id
                    },
                    success: function(response) {

                        if (response.success) {
                            reload();
                            toastr["success"](response.success, title_toastr);

                        } else {
                            toastr["error"](response.error, title_toastr);

                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr["error"](textStatus + ': ' + errorThrown,
                            title_toastr);
                    }
                });
            }
        });
        $('.check-id').prop('checked', false);
        $('.check-all-trig').prop('checked', false);
        bulk_delete_trig.prop('disabled', true);

    });


    table.on('click', '.update', function(e) {
        let data = table.row(e.target.closest('tr')).data();

        $.ajax({
            url: "<?= site_url('admin/datatables/kelas/get_id'); ?>",
            data: {
                id: data.id
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                modal_title.text('Edit Data Kelas');
                modal.modal('show');
                $('[name=id]').val(data.id);
                $('[name=kelas]').val(data.kelas);
                $('[name=jurusan]').val(data.jurusan);
            }
        });


        $('.check-id').prop('checked', false);
        $('.check-all-trig').prop('checked', false);
        bulk_delete_trig.prop('disabled', true);



    });


    table.on('draw.dt', function() {
        var info = table.page.info();
        let data = table.rows({
            search: 'applied',
            order: 'applied',
            page: 'applied'
        }).data().toArray();

        table.cells(null, 1, {
            search: 'applied',
            order: 'applied',
            page: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1 + info.start;
        });

        table.cells(null, 0, {
            search: 'applied',
            order: 'applied',
            page: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = '<input class="check-id" type="checkbox" value="' + data[i].id +
                '">';
        });

        let length = data.length;

        if (length <= 0) {
            $('.exampleformat').prop('disabled', true);
        } else {
            $('.exampleformat').prop('disabled', false);
        }
    });


    function reload() {
        table.ajax.reload();


    }



    function tambah() {
        $('#form')[0].reset();
        $('input:hidden').val('');

        modal_title.text('Tambah Data Kelas');
        modal.modal('show');
    };


    $("#form").on("submit", function(event) {
        event.preventDefault();
        let dataform = $(this).serialize();

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('admin/datatables/kelas/save'); ?>',
            data: dataform,
            success: function(data) {
                modal.modal('hide');
                if (data.success) {
                    toastr["success"](data.success, title_toastr);
                    reload();
                } else if (data.errors) {
                    toastr["error"](data.errors, title_toastr);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            }
        });

    });


    $("#form-import").on("submit", function(event) {
        event.preventDefault();
        let formData = new FormData($(this)[0]);

        let submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        let inputFile = $('.custom-file-input');
        let labelFile = $('.custom-file-label');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('admin/datatables/kelas/import'); ?>',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                showResponseToast(response);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            },
            complete: function(jqXHR, textStatus, errorThrown) {
                setTimeout(function() {
                    submitButton.prop('disabled', false);

                }, 2000);

            }


        });

        labelFile.text('Import file');
        inputFile.val('');

    });


    function showResponseToast(response) {
        setTimeout(function() {
            if (response.success) {
                toastr["success"](response.success, title_toastr);
                reload()
            } else if (response.error) {
                toastr["error"](response.error, title_toastr);

                if (response.problem) {
                    toastr["error"](response.problem, title_toastr);
                }

                if (Array.isArray(response.solution)) {
                    for (var i = 0; i < response.solution.length; i++) {
                        let data = response.solution[i];
                        if (data.column && data.row) {
                            toastr["error"]("Cells: " + data.column + data.row,
                                title_toastr);
                        } else if (data.exists_data) {
                            toastr["error"]("Existing Data: " + data.exists_data, title_toastr);
                        } else if (data.not_exists_data) {
                            toastr["error"]("Not Existing Data: " + data.not_exists_data, title_toastr);
                        }
                    }
                }
            }
        }, 2000);
    }





    let bulk_delete_trig = $('.bulk-delete');

    function checboxbulkdeletetoggle() {


        if ($('.check-all-trig:checked').length > 0 || $('.check-id:checked').length > 0) {
            bulk_delete_trig.prop('disabled', false);
        } else {
            bulk_delete_trig.prop('disabled', true);
        }
    }

    $(document).on('change', '.check-id', checboxbulkdeletetoggle);
    $(document).on('change', '.check-all-trig', checboxbulkdeletetoggle);

    function bulk_delete() {
        let selected = [];
        $('.check-id:checked').each(function() {
            let list = $(this).val();
            selected.push(list);
        });


        Swal.fire({
            title: 'Apakah ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '<?= site_url('admin/datatables/kelas/bulk_delete'); ?>',
                    data: {
                        array_id: selected
                    },
                    success: function(response) {
                        if (response.success) {
                            reload();

                            toastr["success"](response.success, title_toastr);


                        } else if (response.error) {
                            toastr["error"](response.error, title_toastr);

                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
                    }
                });
            }
        });

        $('.check-id').prop('checked', false);
        $('.check-all-trig').prop('checked', false);
        bulk_delete_trig.prop('disabled', true);


    }

});
</script>