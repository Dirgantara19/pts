<div class="container-fluid">
    <div class="row">

        <div class="col-4">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title mb-3 ">User Information</h5>

                    <img src="" class="user-img img-thumbnail" alt="">
                    <p class="card-text text-center user-fullname-title mt-2"></p>

                </div>
            </div>

        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header text-center">
                    <h6 class="card-title">Update Data</h6>
                </div>
                <form action="" method="post" id="form">
                    <div class="card-body">
                        <div id="loading" class="loading">
                            <div class="loading-spinner"></div>
                            <p>Processing...</p>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="full_name">Fullname and ect.</label>
                                <input class="form-control" type="text" id="full_name" name="full_name" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="nip_or_nik">NIP/NIK</label>
                                <input class="form-control" type="nip_or_nik" id="nip_or_nik" name="nip_or_nik" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><b>Image</b></div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <img src="" class="img-thumbnail img-preview">
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="custom-file">
                                            <input class="custom-file-input" type="file" id="userfile" name="userfile"
                                                onchange="readURL(this);">
                                            <label class="custom-file-label" for="userfile">Choose file!</label>
                                        </div>
                                        <div class="mt-3">
                                            <input class="form-control" type="hidden" id="defaultimg"
                                                name="defaultimg" />
                                            <button type="button"
                                                class="btn btn-sm btn-danger deleteimg">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-warning row">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control" type="password" id="password" name="password" />
                                <div class="form-control-feedback">If want change password</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="password_confirm">Confirm Password</label>
                                <input class="form-control" type="password" id="password_confirm"
                                    name="password_confirm" />
                                <div class="form-control-feedback">If want change password</div>
                            </div>
                        </div>



                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
let title_toastr = "User Information";
$.ajax({
    url: '<?= site_url('profile/user_info'); ?>',
    type: 'get',
    dataType: 'json',
    success: function(data) {
        if (data.img == 'gambar.png') {
            $('.deleteimg').prop('disabled', true);
        }
        setTimeout(function() {
            $('[name=full_name]').val(data.full_name);
            $('.img-preview').attr('src', '<?= $theme_url; ?>' + 'img/profile/' + data.img);
            $('[name=nip_or_nik]').val(data.nip_or_nik);
            $('[name=id]').val(data.id);
            $("#loading").hide();
        }, 2000);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
        $("#loading").hide();
    },
    complete: function() {
        $("#loading").show();
    }
});



$('#form').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: '<?= site_url('profile/save'); ?>',
        type: 'POST',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                toastr["success"](response.success, title_toastr);
            } else if (response.errors) {
                toastr["error"](response.errors, title_toastr);
            }
            let inputFile = $('.custom-file-input');
            let labelFile = $('.custom-file-label');

            labelFile.text('');

            user_info();
        }

    });
});



function readURL(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function(e) {
            $('.img-preview')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}


$('.deleteimg').on('click', function() {
    let nameFile = 'gambar.png';

    $.ajax({
        url: '<?= site_url('profile/default_profile'); ?>',
        type: 'post',
        data: {
            img: nameFile
        },
        dataType: 'json',
        success: function(response) {
            setTimeout(function() {

                if (response.success) {
                    toastr["success"](response.success, title_toastr);
                } else if (response.errors) {
                    toastr["error"](response.errors, title_toastr);
                }

                user_info();
                let defaultIMG = '<?= $theme_url . 'img/profile/gambar.png'; ?>';
                $('.img-preview').attr('src', defaultIMG);
                $('[name=defaultimg]').val(nameFile);
                $("#loading").hide();

            }, 2000);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            toastr["error"](textStatus + ': ' + errorThrown, title_toastr);
            $("#loading").hide();
        },
        complete: function() {
            $("#loading").show();
        }
    });
});
</script>