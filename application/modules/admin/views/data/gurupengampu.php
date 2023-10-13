<div class="container-fluid">
    <!-- Button trigger modal -->
    <div class="col-sm-6">

        <div class="row">

            <form action="" id="form-import" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <div class="input-group-prepend">

                        <button class="custom-btn btn-warning" type="submit" id="inputGroupFileAddon03"> <i
                                class="fas fa-fw fa-download"></i>
                        </button>
                    </div>
                    <div class="custom-file">
                        <label class="custom-file-label" for="inputGroupFile03">Import file</label>
                        <input type="file" name="filegurupengampu" class="custom-file-input" id="inputGroupFile03"
                            aria-describedby="inputGroupFileAddon03">
                    </div>

                </div>
            </form>

        </div>

    </div>
    <div class="mt-2">
        <table class="table table-sm table-bordered" id="table-example3">
            <thead>
                <th><input class="check-all-trig" type='checkbox' id=''></th>

                <th>No</th>
                <th>Guru Pengampu</th>
                <th>NIP/NIK</th>
                <th>Mengajar</th>
                <th>Kelas</th>
                <th>Action</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="modalgurupengampu" tabindex="-1" aria-labelledby="modalgurupengampulabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalgurupengampulabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="form">
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="hidden" name="id" id="id">
                        <label class="form-label" for="user_id">Guru Pengampu</label>
                        <select class="form-control selectpicker show-tick" data-live-search="true" name="user_id"
                            id="user_id" title="Search guru...">
                            <?php foreach ($guru as $gu) : ?>
                            <option value="<?= $gu->id; ?>"><?= $gu->guru ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mapel_id">Mapel</label>
                        <select class="form-control selectpicker show-tick" data-live-search="true" name="mapel_id"
                            id="mapel_id" title="Search mapel...">
                            <?php foreach ($mapel as $ma) : ?>
                            <option value="<?= $ma->id; ?>"><?= $ma->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
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

<?= $this->load->view('admin/datatables/gurupengampu'); ?>