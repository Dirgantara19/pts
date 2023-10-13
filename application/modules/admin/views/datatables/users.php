<script type="text/javascript">
    let modal = $('#modalusers');
    let modal_title = $('#modaluserslabel');
    let title_toastr = "User Information";

    $(document).ready(function() {

        let table = new DataTable('.user-table', {
            processing: true,
            serverSide: true,
            ordering: true,
            responsive: true,
            order: [],
            'dom': "<'row d-flex justify-content-between'<'col-sm-12 col-md-4 d-flex align-items-center'B><'col-sm-12 col-md-4 'l><'col-sm-12 col-md-4'f>>" +
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
                    },

                    {
                        text: '<i class="fas fa-fw fa-print"></i> Example Format',
                        action: function(e, dt, node, config) {
                            var $a = $("<a>");
                            $a.attr("href",
                                '<?= base_url('admin/export/users'); ?>');
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
                url: '<?php echo base_url('admin/datatables/users/ajax_list'); ?>',
                type: 'post',

            },
            columns: [{
                    'data': null,
                    searchable: false,
                    orderable: false,
                }, {
                    data: null,
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'guru',
                },
                {
                    data: 'nip_or_nik'
                },
                {
                    data: 'groups',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'active_status',
                    render: function(data, type, row, meta) {
                        let a = "";

                        if (data != 1) {
                            a += '<a class="btn btn-sm btn-success activate">Activate</a>';
                        } else {
                            a += '<a class="btn btn-sm btn-danger deactivate">Deactivate</a>';
                        }
                        return a;
                    },
                    searchable: false,
                    orderable: false,
                },
                {
                    data: null,
                    defaultContent: '<a class="btn btn-sm btn-outline-danger mb-1 mr-1 delete"">Delete</a> <a class="btn btn-sm btn-outline-success mr-1 update"">Update</a>',
                    searchable: false,
                    orderable: false,
                }
            ],
            deferRender: true,


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
                        url: '<?= base_url('admin/datatables/users/delete'); ?>',
                        data: {
                            id: data.id
                        },
                        success: function(data) {
                            if (data.success == true) {
                                toastr["success"](data.message, title_toastr);
                                reload();
                            } else {
                                toastr["error"](data.message, title_toastr);
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
            $('#form')[0].reset();

            let data = table.row(e.target.closest('tr')).data();

            $.ajax({
                url: "<?= base_url('admin/datatables/users/get_id'); ?>",
                data: {
                    id: data.id,
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    $('.form-control-feedback').css('display', 'block');
                    modal_title.html('Edit Data User' + ' : </br>' + '<b>' + data.user
                        .full_name + '</b>');
                    modal.modal('show');
                    let userData = data.user;

                    $.each(userData, function(key, value) {
                        if (key !== 'password') {
                            $('[name=' + key + ']').val(value);
                        }
                    });
                    $('[name=email]').attr("readonly", true);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
                }
            });

            $('.check-id').prop('checked', false);
            $('.check-all-trig').prop('checked', false);
            bulk_delete_trig.prop('disabled', true);






        });

        let isProcessing = false;


        table.on('click', '.deactivate', function(e) {


            if (isProcessing) return;

            isProcessing = true;
            let data = table.row(e.target.closest('tr')).data();

            $.post('<?= base_url('admin/datatables/users/deactivate'); ?>', {
                    id: data.id
                }, function(data) {
                    reload();
                }, 'json')
                .done(function() {
                    setTimeout(function() {
                        toastr["success"]("Success: Account Deactivated!.", title_toastr);
                        isProcessing = false;
                    }, 500);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
                });

        });

        table.on('click', '.activate', function(e) {



            if (isProcessing) return;
            isProcessing = true;
            let data = table.row(e.target.closest('tr')).data();


            $.post('<?= base_url('admin/datatables/users/activate'); ?>', {
                    id: data.id
                }, function(data) {
                    reload();
                }, 'json')
                .done(function() {
                    setTimeout(function() {
                        toastr["success"]("Success: Account Activated!.", title_toastr);
                        isProcessing = false;
                    }, 500);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
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
                cell.innerHTML = '<input class="check-id" type="checkbox" value="' + data[i].nis +
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
            $('.form-control-feedback').css('display', 'none');
            $('input:hidden').val('');
            $('[name=email]').removeAttr("readonly");
            modal_title.text('Tambah Data User');
            modal.modal('show');
        };



        $("#form").on("submit", function(event) {
            event.preventDefault();
            let dataform = $(this).serialize();

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '<?= base_url('admin/datatables/users/save'); ?>',
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
                url: '<?= base_url('admin/datatables/users/import'); ?>',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    setTimeout(function() {

                        submitButton.prop('disabled', false);

                        if (data.success) {
                            reload();
                            toastr["success"](data.success, title_toastr);
                        } else if (data.error) {
                            if (data.problem) {

                                if (Array.isArray(data.solution)) {
                                    var colToFit = [];
                                    var cellsToEliminate = [];

                                    for (var i = 0; i < data.solution.length; i++) {
                                        let cells = data.solution[i];
                                        let col = cells.column;
                                        if (cells.row) {
                                            let row = cells.row;
                                            cellsToEliminate.push(col + '' + row);
                                        } else {
                                            colToFit.push(col);

                                        }

                                    }
                                    toastr.options.timeOut = 6000;

                                    if (data.type == 1) {

                                        toastr["error"]("Cells to eliminate: " +
                                            cellsToEliminate
                                            .join(', '),
                                            title_toastr);
                                    } else if (data.type == 2) {
                                        toastr["error"]("Cells to fill: " +
                                            cellsToEliminate.join(
                                                ', '),
                                            title_toastr);
                                    } else if (data.type == 3) {
                                        toastr["error"]("Cells to fit: " + colToFit
                                            .join(', '),
                                            title_toastr);
                                    } else {
                                        toastr["error"](data.solution, title_toastr);
                                    }

                                }

                                toastr.options.timeOut = 4000;
                                toastr["error"](data.problem, title_toastr);
                            }
                            toastr.options.timeOut = 2000;
                            toastr["error"](data.error, title_toastr);

                        }

                    }, 2000)

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
                }
            });

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
                        url: '<?= base_url('admin/datatables/users/bulk_delete'); ?>',
                        data: {
                            array_id: selected
                        },
                        success: function(data) {
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
                }
            });

            $('.check-id').prop('checked', false);
            $('.check-all-trig').prop('checked', false);
            bulk_delete_trig.prop('disabled', true);



        }

    });
</script>