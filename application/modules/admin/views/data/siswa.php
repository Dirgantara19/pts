<div class="container-fluid">
    <!-- Button trigger modal -->
    <div class="col-sm-12">
        <div class="row">

            <form action="<?= base_url('admin/datatables/siswa/import') ?>" id="form-import" method="post"
                enctype="multipart/form-data">
                <div class="input-group">
                    <div class="input-group-prepend">

                        <button class="custom-btn btn-warning" type="submit" id="inputGroupFileAddon03"> <i
                                class="fas fa-fw fa-download"></i>
                        </button>
                    </div>
                    <div class="custom-file">
                        <label class="custom-file-label" for="inputGroupFile03">Import file</label>
                        <input type="file" name="filesiswa" class="custom-file-input" id="inputGroupFile03"
                            aria-describedby="inputGroupFileAddon03">
                    </div>
                </div>
            </form>

        </div>


    </div>
    <div class="mt-2">
        <table class="table table-sm table-responsive-md table-bordered" id="table-example3">
            <thead>
                <th><input class="check-all-trig" type='checkbox' id=''></th>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Action</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>
<!-- Modal -->


<div class="modal fade" id="modalsiswa" tabindex="-1" aria-labelledby="modalsiswalabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalsiswalabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="form">
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="hidden" name="id" id="id">

                        <label class="form-label" for="nama">Nama</label>
                        <input class="form-control" type="text" name="nama" id="nama">

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nis">NIS</label>
                        <input class="form-control" type="num" name="nis" id="nis">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select class="form-control selectpicker show-tick" data-live-search="true" name="kelas_id"
                            id="kelas_id" title="Search kelas...">
                            <?php foreach ($kelas as $ke) : ?>
                            <option value="<?= $ke->id; ?>"><?= $ke->kelas; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<?= $this->load->view('admin/datatables/siswa'); ?>