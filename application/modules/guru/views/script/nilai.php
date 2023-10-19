<script type="text/javascript">
    let title_toastr = "Nilai Information";

    $(document).ready(function() {
        $('#kelas-mapel').selectpicker();

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
                    }


                ],
            },
            ajax: {
                url: "<?= site_url('guru/datatables/nilai/ajax_list'); ?>",
                type: "POST",
                data: function(d) {
                    getAjaxData(d);
                }
            },
            deferRender: true,

            columns: [{
                    data: null,
                    width: '2%',
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
                    data: 'nama'
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'nilai',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        let nilai = '<input type="number" name="' + row.raport_id + '" value="' +
                            data + '">';

                        return nilai;
                    }

                }, {
                    data: null,
                    orderable: false,
                    searchable: false,
                    defaultContent: '<button class="btn btn-sm btn-success update">Update</button>'
                }
            ]
        });


        table.on('click', '.update', function(e) {

            let data = table.row(e.target.closest('tr')).data();

            let nilai = $('[name="' + data.raport_id + '"]').val();


            $.post("<?= site_url('guru/datatables/nilai/update_nilai_by_id'); ?>", {
                'id': data.raport_id,
                'nilai': nilai
            }, function(response) {

                reload();
                setTimeout(function() {

                    if (response.success) {
                        toastr['success'](response.success, title_toastr);
                    } else {
                        toastr['error'](response.error, title_toastr);
                    }

                }, 2000)

            }, 'json');



        });


        $('#kelas-mapel').change(function() {
            reload();
        });


        $('.reload').click(function() {
            reload();
        });

        function reload() {
            table.ajax.reload();

        }



    });
</script>