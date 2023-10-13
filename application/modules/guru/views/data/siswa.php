<div class="container-fluid">
    <!-- Button trigger modal -->
    <div class="col-lg-12">

        <div class="row">

            <form action="" id="form-import" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <div class="input-group-prepend">

                        <button class="custom-btn btn-warning importbutton" type="submit" id="inputGroupFileAddon03"> <i
                                class="fas fa-fw fa-download"></i>
                        </button>
                    </div>
                    <div class="custom-file">
                        <label class="custom-file-label" for="inputGroupFile03">Import file</label>
                        <input type="file" name="fileraport" class="custom-file-input importinput" id="inputGroupFile03"
                            aria-describedby="inputGroupFileAddon03">
                    </div>
                </div>
            </form>

        </div>

    </div>
    <div class="mt-2 col-sm-4">
        <div class="form-group">
            <select class="form-control form-control-sm" id="tahunsemester" name="option" data-live-search="true">

                <option value="" selected disabled>Semester</option>
                <?php foreach ($tahunsemester as $data) : ?>
                <option data-tahunsemester="<?= $data->id; ?>">
                    <?= $data->thn_ajaran . ' ' . ' (' . $data->semester . ') ';  ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <div class="form-group">
            <select class="form-control form-control-sm" id="kelas-mapel" name="option" data-live-search="true">

                <option value="" selected disabled>Kelas dan Mapel</option>
                <?php foreach ($users as $user) : ?>
                <?php if (!$this->ion_auth->is_admin() && !$this->ion_auth->is_programmer()) : ?>
                <option data-kelasid="<?= $user->kelas_id; ?>" data-mapelid="<?= $user->mapel_id; ?>">
                    <?= $user->kelas . ' ' . $user->mapel; ?></option>
                <?php else : ?>
                <option data-kelasid="<?= $user->kelas_id; ?>" data-mapelid="<?= $user->mapel_id; ?>">
                    <?= $user->kelas . ' ' . $user->mapel . ' (' . $user->full_name . ') ';  ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>

        </div>

    </div>
    <div class="mt-1">

        <table class="table table-sm table-bordered" id="table-example3">
            <thead>
                <th>No</th>
                <th>Guru</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>

</div>

<?= $this->load->view('guru/script/siswa'); ?>