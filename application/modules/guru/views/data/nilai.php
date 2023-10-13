<div class="container-fluid">
    <!-- Button trigger modal -->
    <div class="mt-2 col-sm-4">
        <div class="form-group">
            <select class="form-control form-control-sm" id="kelas-mapel" name="option" data-live-search="true"
                title="Search Kelas dan Mapel..">

                <?php foreach ($users as $user) : ?>
                <?php if (!$this->ion_auth->is_admin() && !$this->ion_auth->is_programmer()) : ?>
                <option data-kelasid="<?= $user->kelas_id; ?>" data-mapelid="<?= $user->mapel_id; ?>">
                    <?= $user->kelas . ' ' . $user->mapel; ?></option>
                <?php else : ?>
                <option data-kelasid="<?= $user->kelas_id; ?>" data-mapelid="<?= $user->mapel_id; ?>">
                    <?= $user->kelas . ' ' . $user->mapel . ' (' . $user->full_name . ') '; ?></option>
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
                <th>Mapel</th>
                <th>Kelas</th>
                <th>Nilai</th>
                <th>Action</th>
            </thead>
            <tbody id="table-body">
            </tbody>
        </table>
    </div>

</div>




<?= $this->load->view('guru/script/nilai'); ?>