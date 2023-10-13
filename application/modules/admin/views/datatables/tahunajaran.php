<script type="text/javascript">
let table = $('#table-example3');
let card_title = $('.card-title');
let title_toastr = "Tahun Information";

$(document).ready(function() {




    let table = new DataTable('#table-example3', {
        'processing': true,
        'serverSide': true,
        ordering: true,
        order: [],
        'dom': "<'row'<'col-sm-12 col-md-4 d-flex align-items-center'B><'col-md-8 d-flex justify-content-between col-sm-12'l f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: {
            'buttons': [{
                    text: 'Delete',
                    action: function(e, dt, node, config) {
                        bulk_delete();
                    },
                    attr: {
                        class: 'btn btn-sm mr-1 btn-outline-danger bulk-delete',
                        disabled: 'disabled',
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
                }


            ],
        },
        'reponsive': true,
        'ajax': {
            'url': "<?= base_url('admin/datatables/tahunajaran/ajax_list'); ?>",
            'type': 'post'
        },
        'deferRender': true,
        'keys': true,
        'columns': [{
                data: null,
                'width': '3%',
                searchable: false,
                orderable: false,
            }, {
                'data': null,
                searchable: false,
                orderable: false,
                'width': '3%'
            },
            {
                'data': 'thn_ajaran'
            },
            {
                'data': 'semester'
            },
            {
                'data': null,
                defaultContent: '<a class="btn btn-sm btn-outline-danger mr-1 delete"">Delete</a> <a class="btn btn-sm btn-outline-success mr-1 update"">Update</a>',
                searchable: false,
                orderable: false,
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
                    url: '<?= base_url('admin/datatables/tahunajaran/delete'); ?>',
                    data: {
                        id: data.id
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
                        toastr["error"](textStatus + ': ' + errorThrown,
                            title_toastr);
                    }
                });
            }
        });
    });

    $('.clear').on('click', function() {
        card_title.text('Insert Data');

        $('input:hidden').val('');

        $("#loading").show();


        setTimeout(function() {
            $("#loading").hide();

        }, 500);

    });


    table.on('click', '.update', function(e) {
        let data = table.row(e.target.closest('tr')).data();


        $.ajax({
            url: "<?= base_url('admin/datatables/tahunajaran/get_id'); ?>",
            data: {
                id: data.id
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                setTimeout(function() {
                    card_title.text('Update Data');
                    $('[name=id]').val(data.id);
                    $('[name=thn_ajaran]').val(data.thn_ajaran);
                    $('[name=semester]').val(data.semester);
                    $("#loading").hide();

                }, 2000)

            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#loading").hide();

                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            },
            complete: function() {
                $("#loading").show();
            }


        });

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
            cell.innerHTML =
                '<input class="check-id" type="checkbox" value="' +
                data[i].id +
                '">';
        });

        let length = data.length;

        if (length <= 0) {
            $('.exampleformat').prop('disabled', true);
        } else {
            $('.exampleformat').prop('disabled', false);
        }
    });


    $(document).on('change', '.check-id', checboxbulkdeletetoggle);
    $(document).on('change', '.check-all-trig', checboxbulkdeletetoggle);

    function reload() {
        table.ajax.reload();
    }



    $("#form").on("submit", function(event) {
        event.preventDefault();
        let dataform = $(this).serialize();

        $.post('<?= base_url('admin/datatables/tahunajaran/save'); ?>', dataform, {
                dataType: 'json',
            })
            .done(function(data) {
                setTimeout(function() {

                    let object = jQuery.parseJSON(data);
                    if (object.success) {
                        reload();
                        toastr["success"](object.success, title_toastr);
                    } else if (object.errors) {
                        toastr["error"](object.errors, title_toastr);
                    }

                }, 300);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            })

    });



    function checboxbulkdeletetoggle() {

        let bulk_delete_trig = $('.bulk-delete');

        if ($('.check-all-trig:checked').length > 0 || $('.check-id:checked').length > 0) {
            bulk_delete_trig.prop('disabled', false);
        } else {
            bulk_delete_trig.prop('disabled', true);
        }
    }


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
                    url: '<?= base_url('admin/datatables/tahunajaran/bulk_delete'); ?>',
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
    }

});
</script>