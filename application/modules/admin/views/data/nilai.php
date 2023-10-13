<div class="container-fluid">
    <div class="mt-2 d-flex col-sm-4">


        <select class="form-control form-control-sm" id="kelas" name="option" data-live-search="true"
            title="Search kelas...">

            <?php foreach ($kelas as $kelas) : ?>
            <option value="<?= $kelas->id; ?>">
                <?= $kelas->kelas ?></option>

            <?php endforeach; ?>
        </select>
        <select class="form-control form-control-sm" id="tahun" name="option" data-live-search="true"
            title="Search tahun...">

            <?php foreach ($tahun as $tahun) : ?>
            <option value="<?= $tahun->id; ?>">
                <?= $tahun->thn_ajaran . ' ' . $tahun->semester ?></option>

            <?php endforeach; ?>
        </select>
    </div>
    <div class="mt-2">
        <table class="table table-sm table-bordered" id="table-example3">
            <thead>
                <th>No</th>
                <th>NIS</th>
                <th>Siswa</th>
                <?php foreach ($mapel as $data) : ?>
                <th><?= $data->sing; ?></th>
                <?php endforeach; ?>
                <th>Total</th>
                <th>Action</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="mt-3 update-card">
        <div class="card">
            <div class="card-header text-center">
                <h6 class="card-title">Update Data</h6>
            </div>
            <form action="" method="post" id="form">
                <div class="card-body">
                    <!-- Processing Nilai... -->
                    <div id="loading" class="loading">
                        <div class="loading-spinner"></div>
                        <p>Processing...</p>
                    </div>

                    <table class="table table-sm table-bordered" id="tableedit">
                        <thead>

                            <?php foreach ($mapel as $data) : ?>
                            <th><?= $data->sing; ?></th>
                            <?php endforeach; ?>
                        </thead>
                        <tbody>
                            <tr>
                                <div class="form-group">
                                    <input type="hidden" id="nis" name="nis">
                                </div>
                                <div class="form-group">
                                    <?php foreach ($mapel as $data) : ?>
                                    <td><input class="form-control form-control-sm inputnilai" type="number"
                                            name="<?= $data->slug; ?>" id="<?= $data->nama; ?>" readonly></td>
                                    <?php endforeach; ?>
                                </div>
                            </tr>
                        </tbody>

                    </table>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>


<?= $this->load->view('admin/datatables/nilai'); ?>