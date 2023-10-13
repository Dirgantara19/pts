<script type="text/javascript">
let modal = $('#modalgurupengampu');
let modal_title = $('#modalgurupengampulabel');
let title_toastr = "Gurupengampu Information";
$(document).ready(function() {

    let table = new DataTable('#table-example3', {
        processing: true,
        serverSide: true,
        ordering: true,
        order: [5, 'asc'],
        rowGroup: true,
        responsive: true,
        rowGroup: {
            dataSrc: ['kelas'],
            startRender: function(rows, group) {
                return group + ' (' + rows.count() + ' rows)';
            }
        },
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
                        $a.attr("href",
                            '<?= base_url('admin/export/gurupengampu'); ?>');
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

        ajax: {
            'url': '<?= base_url('admin/datatables/gurupengampu/ajax_list'); ?>',
            'type': 'post'
        },
        deferRender: true,
        columns: [{
                data: null,
                'width': '3%',
                searchable: false,
                orderable: false,
            }, {
                data: null,
                'width': '3%',
                searchable: false,
                orderable: false,
            },
            {
                data: 'guru'
            },
            {
                data: 'nip_or_nik'
            },
            {
                data: 'mengajar'
            },
            {
                data: 'kelas'
            },
            {
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: '<a class="btn btn-sm btn-outline-danger mr-1 delete"">Delete</a> <a class="btn btn-sm btn-outline-success mr-1 update"">Update</a>',

            },
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
                    url: '<?= base_url('admin/datatables/gurupengampu/delete'); ?>',
                    data: {
                        id: data.id
                    },
                    success: function(data) {
                        if (data.success) {
                            reload();
                            toastr["success"](data.success);

                        } else {
                            toastr["error"](data.error);

                        }
                    },
                    error: function() {

                        toastr["error"](data.error);


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


        $.post("<?= base_url('admin/datatables/gurupengampu/get_id'); ?>", {
                id: data.id
            }, {
                dataType: 'json'
            })
            .done(function(data) {
                let object = jQuery.parseJSON(data);
                modal_title.text('Edit Data Guru Pengampu');
                modal.modal('show');

                var inputElements = {
                    'id': $('[name=id]'),
                    'user_id': $('#user_id'),
                    'kelas_id': $('#kelas_id'),
                    'mapel_id': $('#mapel_id')
                };

                $.each(inputElements, function(key, element) {
                    if (key === 'id') {
                        element.val(object[key]);
                    } else {
                        element.selectpicker('val', object[key]);
                    }
                });
            })

            .fail(function(jqXHR, textStatus, errorThrown) {
                modal.modal('hide');
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            })


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

    $("#user_id").selectpicker();
    $("#mapel_id").selectpicker();
    $("#kelas_id").selectpicker();



    function tambah() {
        $('#form')[0].reset();
        $('input:hidden').val('');
        $('#user_id').selectpicker('refresh');
        $('#kelas_id').selectpicker('refresh');
        $('#mapel_id').selectpicker('refresh');

        modal_title.text('Tambah Data Guru Pengampu');
        modal.modal('show');
    };



    $("#form").on("submit", function(event) {
        event.preventDefault();
        let dataform = $(this).serialize();

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= base_url('admin/datatables/gurupengampu/save'); ?>',
            data: dataform,
            success: function(data) {
                setTimeout(function() {
                    reload();
                    modal.modal('hide');
                    if (data.success) {
                        toastr["success"](data.success, title_toastr);
                    } else if (data.errors) {
                        toastr["error"](data.errors, title_toastr);
                    }

                }, 300);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                modal.modal('hide');
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            }
        });

    });

    $("#form-import").on("submit", function(event) {
        event.preventDefault();

        let submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);
        let formData = new FormData($(this)[0]);

        let inputFile = $('.custom-file-input');
        let labelFile = $('.custom-file-label');

        $.ajax({
                url: '<?= base_url() . 'admin/datatables/gurupengampu/import'; ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
            }).done(function(response) {
                setTimeout(function() {

                    submitButton.prop('disabled', false);

                    if (response.success) {
                        reload();
                        toastr["success"](response.success, title_toastr);
                    } else if (response.error) {
                        if (response.problem) {

                            if (Array.isArray(response.solution)) {
                                console.log(response);
                                var colToFit = [];
                                var cellsToEliminate = [];
                                var existingData = [];
                                for (var i = 0; i < response.solution.length; i++) {
                                    let data = response.solution[i];
                                    let col = data.column;
                                    if (data.exists_data) {
                                        let exists_data = data.exists_data;
                                        existingData.push(exists_data);

                                    } else if (data.row) {
                                        let row = data.row;
                                        cellsToEliminate.push(col + '' + row);
                                    } else {
                                        colToFit.push(col);

                                    }

                                }
                                toastr.options.timeOut = 6000;

                                if (response.type == 1) {

                                    toastr["error"]("Cells to eliminate: " +
                                        cellsToEliminate
                                        .join(', '),
                                        title_toastr);
                                } else if (response.type == 2) {
                                    toastr["error"]("Cells to fill: " +
                                        cellsToEliminate.join(
                                            ', '),
                                        title_toastr);
                                } else if (response.type == 3) {
                                    toastr["error"]("Cells to fit: " + colToFit
                                        .join(', '),
                                        title_toastr);
                                } else if (response.type == 4) {
                                    toastr["error"]("Existing Data: " + existingData
                                        .join(', '),
                                        title_toastr);
                                } else {
                                    toastr["error"](response.solution,
                                        title_toastr);
                                }

                            }
                            toastr.options.timeOut = 4000;
                            toastr["error"](response.problem, title_toastr);
                        }
                        toastr.options.timeOut = 2000;
                        toastr["error"](response.error, title_toastr);
                    }
                }, 2000)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            })


        labelFile.text('Import file');
        inputFile.val('');

    });

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
        if (selected) {
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
                        url: '<?= base_url('admin/datatables/gurupengampu/bulk_delete'); ?>',
                        data: {
                            array_id: selected
                        },
                        success: function(data) {
                            if (data.success) {
                                reload();

                                toastr["success"](data.success, title_toastr);

                            } else if (data.error) {
                                toastr["error"](data.error, title_toastr);

                            };
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr["error"](textStatus + ': ' + errorThrown,
                                title_toastr);
                        }
                    });
                }
            });

        }

        $('.check-id').prop('checked', false);
        $('.check-all-trig').prop('checked', false);
        bulk_delete_trig.prop('disabled', true);



    }

});
</script>