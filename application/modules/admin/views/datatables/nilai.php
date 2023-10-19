<script>
var title_toastr = "Nilai Information";

$(document).ready(function() {
    $('#kelas').selectpicker();
    $('#tahun').selectpicker();



    let i = 1;
    let table = new DataTable('#table-example3', {
        processing: true,
        serverSide: true,
        ordering: true,
        order: [
            1, 'desc'
        ],
        responsive: true,
        rowGroup: true,
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

                        let d = {};
                        let data = getAjaxData(d);
                        if (data.kelasid && data.tahunid) {
                            var $a = $("<a>");
                            $a.attr("href",
                                "<?= site_url('admin/export/nilai/'); ?>" +
                                data
                                .kelasid + '/' + data.tahunid);
                            $a[0].click();
                            $a.remove();

                        }

                    },
                    attr: {
                        class: 'btn btn-info btn-sm exampleformat',
                        disabled: 'disabled'
                    }
                },


            ],
        },
        ajax: {
            'url': '<?= site_url('admin/datatables/nilai/ajax_list'); ?>',
            'type': 'post',
            'data': function(d) {
                getAjaxData(d);
            }
        },
        columns: [{
                data: null,
                orderable: false,
                searchable: false,
            },
            {
                "data": "nis"
            },
            {
                "data": "nama"
            },
            <?php foreach ($mapel as $mapelItem) : ?> {
                "data": 'mapel',
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    if (type === 'display' && Array.isArray(row.mapel)) {
                        var nilai = '';
                        row.mapel.forEach(function(item) {
                            if (item.slug === '<?= $mapelItem->slug; ?>') {
                                nilai = item.nilai;
                            }
                        });
                        return nilai;
                    }
                    return data;
                }
            },
            <?php endforeach; ?> {
                "data": "total_nilai",
                searchable: false,
                orderable: false,
            },
            {
                data: null,
                searchable: false,
                orderable: false,
                defaultContent: '<a class="btn btn-sm btn-outline-success mr-1 update">Update</a>',

            }
        ]
    });


    table.on('click', '.update', function(e) {
        let data = table.row(e.target.closest('tr')).data();

        $('#form')[0].reset();
        $('input:hidden').val('');
        $('.inputnilai').prop('readonly', true);

        let nis = data.nis;
        let url = "<?= site_url('admin/datatables/nilai/get_id'); ?>";


        $.post(url, {
                nis: nis
            }, 'json')
            .done(function(response) {
                let data = jQuery.parseJSON(response);
                setTimeout(function() {
                    $('.card-title').html('<h6><b>' + data[0].nis + ' </b>' + ' : ' + data[
                        0].nama + '</h6>');
                    $('[name="nis"]').val(data[0].nis);

                    let nilai = 0;

                    for (let j = 0; j < data[0].mapel.length; j++) {
                        let slug = data[0].mapel[j].slug;
                        let input = $('[name="' + slug + '"]');

                        if (input) {
                            input.prop('readonly', false);
                            input.val(data[0].mapel[j].nilai);
                        }
                    }
                    $("#loading").hide();

                }, 2000);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $("#loading").hide();

                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            })


            .always(function() {
                $("#loading").show();

            })
    });


    table.on('change', '.check-all', function(e) {
        let data = table.row(e.target.closest('tr')).data();
        let id = data.id;
        e.target.value = id;

        checboxbulkdeletetoggle();
    });


    table.on('draw.dt', function() {
        var info = table.page.info();
        table.cells(null, 0, {
            search: 'applied',
            order: 'applied',
            page: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1 + info.start;
        });


        let length = table.data().length;


        if (length <= 0) {
            $('.exampleformat').prop('disabled',
                true);

        } else {

            $('.exampleformat').prop('disabled',
                false);
        }
    });

    $('#kelas').change(function() {
        reload();
    });
    $('#tahun').change(function() {
        reload();
    });


    function reload() {

        table.ajax.reload();
    }

    function checboxbulkdeletetoggle() {

        let bulk_delete_trig = $('.bulk-delete');

        if ($('.check-all-trig:checked').length > 0 || $('.check-all:checked').length > 0) {
            bulk_delete_trig.prop('disabled', false);
        } else {
            bulk_delete_trig.prop('disabled', true);
        }
    }

    $('.check-all-trig').on('change', checboxbulkdeletetoggle);

    function getAjaxData(d) {
        let selectedkelas = $('#kelas').find('option:selected');
        let kelasid = selectedkelas.val();
        let selectedtahun = $('#tahun').find('option:selected');
        let tahunid = selectedtahun.val();

        d.kelasid = kelasid;
        d.tahunid = tahunid;
        return d;
    }

    $("#form").on("submit", function(event) {
        event.preventDefault();

        let formdata = $(this).serialize();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('admin/datatables/nilai/save'); ?>',
            data: formdata,
            success: function(data) {
                if (data.success) {
                    setTimeout(function() {
                        toastr["success"](data.success, title_toastr);
                        reload();
                    }, 500);
                } else if (data.errors) {
                    toastr["error"](data.errors, title_toastr);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            }
        });
    });


});
</script>