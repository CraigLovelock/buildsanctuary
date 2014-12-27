$(document).ready(function(){

  // insert image button for new post
  $(".insert-image").click(function(){
    $('.update-insertimage-btn').trigger('click');
  });

  $(".update-insertimage-btn").change(function(){
    $('.update-insertimage-form').submit();
  });

  // insert image button for editing posts
  $(".edit-insert-image").click(function(){
    $('.edit-insertimage-btn').trigger('click');
  });

  $(".edit-insertimage-btn").change(function(){
    $('.edit-insertimage-form').submit();
  });

  function clearPlaceHolder() {
    if ($(".form-control-addupdate").text().length === 0) {
      $(".form-control-addupdate").empty();
    }
  }

 /*var lastScrollTop = 0;
  $(window).scroll(function(event){
     var st = $(this).scrollTop();
     if (st > lastScrollTop){
        $(".navbar").stop(true, false)
        .animate({ 'marginTop': '-50px'}, 200);
        $(".build-information").stop(true, false)
          .animate({ 'marginTop': '0'}, 200)
          .animate({ 'opacity': '0.8'}, 200)
          .css('backgroundColor', 'white')
          .css('position', 'fixed')
          .css('width', '100%')
          .css('left', '0')
          .css('top', '0px')
          .css('text-align', 'center')
     } else {
        $(".navbar").stop(true, false)
          .animate({ 'marginTop': '0'}, 200);
        $(".build-information").stop(true, false)
          .animate({ 'marginTop': '50px'}, 200);
     }
     lastScrollTop = st;
  });

  var $win = $(window);
  $win.scroll(function () {
    if ($win.scrollTop() == 0) {
      $(".build-information")
        .animate({ 'marginTop': '0'}, 00)
        .css('backgroundColor', '')
        .css('position', '')
        .css('text-align', 'left')
        .css('top', '');
    }
  });
*/

  function updateNewPostField() {
    var content = $('#newupdate-text-1').html();
    $('#newupdate-text').val(content);
  }

  function updateEditPostField() {
    var content = $('#edit-post-editor').html();
    $('#newupdate-text-edit').val(content);
  }

  $('#newupdate-text-1').on('blur keyup keypress input paste change', function(){
    updateNewPostField();
  });

  $('#edit-post-editor').on('blur keyup keypress input paste change', function(){
    updateEditPostField();
  });


  $('#edit-post-editor').on('blur keyup keypress input paste change', function(){
    updateEditPostField();
  });

  $('.newupdateform').submit(function() {
    $(".submit-newupdate-btn").addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    $.ajax({
        url: rootAsset+'createpostaction',
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $('.newupdateform').serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
          $(".submit-newupdate-btn").removeClass('disabled');
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>'); 
          } else if (data.success) {
            location.href= rootAsset+'viewbuild/'+data.buildID+'/'+data.URLSlug+'?page='+data.lastPage;
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".edit-post-btn").on('click', function(){
    $('#editPostModal').modal('show');
    var id = this.id;
    var rootAsset = $('.rootAsset').html();
      $.ajax({
      url: rootAsset+'get-post-data/'+id,
      type: 'post',
      cache: false,
      dataType: 'json',
      data: $(this).serialize(),
      beforeSend: function() {
        $(".modal-error-message").remove();
        $(".submit-newupdate-btn").removeClass('disabled');
      },
      success: function(data) {
        if(data.errors) {
          $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>'); 
        } else if (data.success) {
          $("#edit-post-editor").html(data.postText);
          $("#newupdate-text-edit").val(data.postText);
          $("input[name=postid]").val(data.postid);
        }
      },
      error: function(xhr, textStatus, thrownError) {
          alert('Something went to wrong.Please Try again later...');
      }
    });
    return false;
  });

  $('.update-insertimage-form').submit(function() {
    $(".submit-newupdate-btn").addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    var formData = new FormData($('.update-insertimage-form')[0]);
    var loadingProgress= 0;
    $.ajax({
        url: rootAsset+'saveUploadedImage',
        type: 'post',
        cache: false,
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
          $(".form-control-addupdate").append('<div class="uploading-overlay">Uploading Image... '+loadingProgress+'%</div>');
          $(".uploading-overlay").fadeIn();
          setInterval(function () {
            if (loadingProgress < 95) {
              ++loadingProgress;
            }
            $(".uploading-overlay").text("Uploading Image..."+loadingProgress+"%");
          }, 50);
        },
        success: function(data) {
          $(".submit-newupdate-btn").removeClass('disabled');
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            $(".form-control-addupdate").append('<img class="temp_added_image" src="/public_html/user_uploads/build_images/'+data.name+'.jpeg"><br><br>');
            var loadingProgress = 100;
            $(".uploading-overlay").text("Uploading Image..."+loadingProgress+"%").fadeOut(function(){
              $(".uploading-overlay").remove(function(){
                updateNewPostField();
              });
            });
            var $t = $('.form-control-addupdate');
            $t.animate({"scrollTop": $('.form-control-addupdate')[0].scrollHeight}, "slow");
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  function resize() {
    var box = $(".update");
    box.find("img.buildimage").on('load', function () {
      var img = $(this),
        width = img.width();
      if (width >= 801) {
        img.addClass("buildimage-large");
      } else if (width < 800 && width > 101) {
        img.addClass("buildimage-small");
      }
      // if image is less than X, its most likely a smiley
      else if (width < 100) {
        img.addClass("buildimage-smiley");
      }
      }).filter(function () {
        //if the image is already loaded manually trigger the event
        return this.complete;
    }).trigger('load');
  }
  resize();

  $('.edit-insertimage-form').submit(function() {
    $(".submit-newupdate-btn").addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    var formData = new FormData($('.edit-insertimage-form')[0]);
    var loadingProgress = 0;
    $.ajax({
      url: rootAsset+'saveUploadedImageEdit',
      type: 'post',
      cache: false,
      dataType: 'json',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $(".form-control-editupdate").append('<div class="uploading-overlay">Uploading Image... '+loadingProgress+'%</div>');
        $(".uploading-overlay").fadeIn();
        setInterval(function () {
          if (loadingProgress < 95) {
            ++loadingProgress;
          }
          $(".uploading-overlay").text("Uploading Image..."+loadingProgress+"%");
        }, 50);
      },
      success: function(data) {
        $(".submit-newupdate-btn").removeClass('disabled');
        if(data.errors) {
          $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
        } else if (data.success) {
          $(".form-control-editupdate").append('<img class="temp_added_image" src="/public_html/user_uploads/build_images/'+data.name+'.jpeg"><br><br>');
          var loadingProgress = 100;
          $(".uploading-overlay").text("Uploading Image..."+loadingProgress+"%").fadeOut();
          $(".uploading-overlay").remove();
          var $t = $('.form-control-editupdate');
          $t.animate({"scrollTop": $('.form-control-editupdate')[0].scrollHeight}, "slow");
        }
      },
      error: function(xhr, textStatus, thrownError) {
          alert('Something went to wrong.Please Try again later...');
      }
     });
    return false;
    });

  $('.editupdateform').submit(function() {
    var $btn = $(".submit-editupdate-btn").val('Saving...');
    var rootAsset = $('.rootAsset').html();
    var postID = $("input[name=postid]").val();
    $.ajax({
        url: rootAsset+'editpostaction/'+postID,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $('.editupdateform').serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            $(".number-"+postID).html(data.newtext);
            $('#editPostModal').modal('hide');
            //location.reload();
          }
          $btn.val('Save Update');
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".delete-update-btn").on('click', function(){
    $('.check-delete-post').fadeToggle();
  });

  $(".dont-delete-post").on('click', function(){
    $(".check-delete-post").fadeOut();
    console.log('dont delete');
  });

  $(".do-delete-post").on('click', function(){
    var rootAsset = $('.rootAsset').html();
    var postID = $("input[name=postid]").val();
    $.ajax({
        url: rootAsset+'deletepost/'+postID,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
          $(".submit-newupdate-btn").removeClass('disabled');
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            location.reload();
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $('.follow-button-form').submit(function() {
    var rootAsset = $('.rootAsset').html();
    var buildID = $(".follow-button-form input[name=buildid]").val();
    var userID = $(".follow-button-form input[name=userid]").val();
    $.ajax({
        url: rootAsset+'followbuild/'+buildID+'/'+userID,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
        },
        success: function(data) {
          if(data.errors) {
            // no errors possible... lol.
          } else if (data.success) {
            if (data.following) {
              $(".follow-button-form .follow-button").removeClass('btn-default')
                .addClass('btn-success')
                .html('Following <span class="glyphicon glyphicon-ok"></span>');
            } else if (data.unfollowing) {
              $(".follow-button-form .follow-button").removeClass('btn-success')
                .addClass('btn-default')
                .html('Follow <span class="glyphicon glyphicon-heart"></span>');
            }
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".user-is-following").on('mouseover', function(){
    $(this).html('Following <span class="glyphicon glyphicon-remove"></span>');
  });

  $(".user-is-following").on('mouseleave', function(){
    $(this).html('Following <span class="glyphicon glyphicon-ok"></span>');
  });

});