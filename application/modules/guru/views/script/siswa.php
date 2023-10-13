<script type="text/javascript">
    let title_toastr = "Siswa Information";

    $(document).ready(function() {
        $('#kelas-mapel').selectpicker();
        $('#tahunsemester').selectpicker();

        function getAjaxData(d) {
            var selectedOption = $('#kelas-mapel').find('option:selected');
            var kelasid = selectedOption.data('kelasid');
            var mapelid = selectedOption.data('mapelid');

            d.kelasid = kelasid;
            d.mapelid = mapelid;


        }



        let table = new DataTable('#table-example3', {
            processing: true,
            serverSide: true,
            ordering: true,
            order: [],
            'dom': "<'row'<'col-sm-12 col-md-4 d-flex align-items-center'B><'col-sm-12 col-md-4 'l><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: {
                'buttons': [

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
                        text: '<i class="fas fa-fw fa-print"></i> Export',
                        action: function(e, dt, node, config) {



                            let ajaxData = {};
                            getAjaxData(ajaxData);
                            var selectedOption = $('#tahunsemester').find(
                                'option:selected');
                            var tahunsemester = selectedOption.data('tahunsemester');


                            if (typeof tahunsemester == 'undefined' || typeof ajaxData
                                .mapelid ==
                                'undefined' && typeof ajaxData.kelasid ==
                                'undefined') {

                                toastr["error"](
                                    'Error: You have to selected kelas, mapel, semester.',
                                    title_toastr);



                            } else {

                                $.post("<?= base_url('guru/datatables/siswa/export'); ?>", {
                                        mapelid: ajaxData.mapelid,
                                        kelasid: ajaxData.kelasid,
                                        tahunid: tahunsemester
                                    },

                                    function(data) {
                                        if (data.status === true) {
                                            var $a = $("<a>");
                                            $a.attr("href", data.file);
                                            $("body").append($a);
                                            $a.attr("download", data.namefile +
                                                ".xlsx");
                                            $a[0].click();
                                            $a.remove();
                                        } else {
                                            toastr["error"](data.error, title_toastr);
                                        }
                                    }, 'json');


                            }

                        },
                        attr: {
                            class: 'btn btn-info btn-sm exportsiswa',
                            disabled: 'disabled',
                        }
                    }


                ],
            },
            ajax: {
                url: "<?= base_url('guru/datatables/siswa/ajax_list'); ?>",
                type: "POST",
                data: function(d) {
                    getAjaxData(d);
                }
            },
            deferRender: true,

            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'full_name'
                },
                {
                    data: 'nama_siswa'
                },
                {
                    data: 'nis'
                },
                {
                    data: 'kelas'
                }
            ]
        });

        $('#kelas-mapel , #tahunsemester').change(function() {
            let ajaxData = {};
            getAjaxData(ajaxData);
            var selectedOption = $('#tahunsemester').find('option:selected');
            var tahunsemester = selectedOption.data('tahunsemester');


            if (typeof tahunsemester == 'undefined' || typeof ajaxData.mapelid ==
                'undefined' && typeof ajaxData.kelasid ==
                'undefined') {

                $('.exportsiswa').prop('disabled', true);

            } else {

                $('.exportsiswa').prop('disabled', false);

            }
            reload();
        });


        $('.reload').click(function() {
            reload();
        });

        function reload() {
            table.ajax.reload();

        }




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
                url: '<?= base_url('guru/datatables/siswa/import'); ?>',
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
                            toastr["error"](data.error, title_toastr);
                            if (data.existing_data) {

                                toastr["error"]("Existing Data: " + data.existing_data
                                    .join(
                                        ', '),
                                    title_toastr);
                            } else if (data.problem) {
                                if (Array.isArray(data.solution)) {
                                    var cellsToEliminate = [];

                                    for (var i = 0; i < data.solution.length; i++) {
                                        let cells = data.solution[i];
                                        let col = cells.column;
                                        let row = cells.row;

                                        cellsToEliminate.push(col + '' + row);
                                    }
                                    if (data.type == 1) {

                                        toastr["error"]("Cells to eliminate: " +
                                            cellsToEliminate
                                            .join(', '),
                                            title_toastr);
                                    } else if (data.type == 2) {
                                        toastr["error"]("Cells to fill: " +
                                            cellsToEliminate
                                            .join(
                                                ', '),
                                            title_toastr);
                                    } else {
                                        toastr["error"](data.solution, title_toastr);
                                    }

                                }
                            }
                        }

                    }, 2000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
                }
            });



            labelFile.text('Import file');
            inputFile.val('');

        });

    });
</script>