<div class="container-fluid">
    <div class="row">
        <div class="col-4">
            <div class="mt-3 tahunajaran-card">
                <div class="card">
                    <div class="card-header text-center">
                        <h6 class="card-title text-bold">Insert Data</h6>
                    </div>
                    <form action="" method="post" id="form">
                        <div class="card-body">
                            <div id="loading" class="loading">
                                <div class="loading-spinner"></div>
                                <p>Processing...</p>
                            </div>
                            <div class="form-group">

                                <input class="form-control" type="hidden" name="id" id="id">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="thn_ajaran">Tahun</label>
                                <input class="form-control daterange" type="text" name="thn_ajaran" id="thn_ajaran">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="semester">Semester</label>
                                <input class="form-control" type="text" value="" name="semester" id="semester">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="reset" class="btn btn-sm btn-secondary clear">Clear</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-8">

            <table class="table table-sm table-responsive-sm table-bordered" id="table-example3">
                <thead>
                    <th><input class="check-all-trig" type='checkbox' id="check-all-trig"></th>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>Action</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>


    </div>

</div>

<?= $this->load->view('admin/datatables/tahunajaran'); ?>