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

    var dots = 0;
    setInterval (type, 300);

    function type() {
      if(dots < 3) {
        $('#dots').append('.');
        dots++;
      } else {
        $('#dots').html('');
        dots = 0;
      }
    }

  function updateNewPostField() {
    var content = $('#newupdate-text-1').html();
    $('#newupdate-text').html(content);
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
    var $btn = $(".submit-newupdate-btn");
    $btn.addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    $.ajax({
        url: rootAsset+'createpostaction',
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $('.newupdateform').serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
          $btn.val('Posting...');
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            // success and does have access!
            location.href= rootAsset+'viewbuild/'+data.buildID+'/'+data.URLSlug+'?page='+data.lastPage;
          } else if (data.no_access) {
            location.href = ""+rootAsset+"deniedaccess";
          }
          $(".submit-newupdate-btn").removeClass('disabled');
          $btn.val('Post Update');
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  function clearModals(){
    $(".form-control-addupdate").html('');
    $(".form-control-editupdate").html('');
  }

  // fix to show placeholder
  clearModals();
  $('#myModal, #editPostModal').on('hidden.bs.modal', function (e) {
    clearModals();
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
          $("input[name=buildid]").val(data.buildid);
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
    $.ajax({
        url: rootAsset+'saveUploadedImage',
        type: 'post',
        cache: false,
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
          $(".modal-body").append('<div class="uploading-overlay"></div>');
          $(".uploading-overlay").fadeIn();
          $(".uploading-overlay").html("Uploading Image<div id='#dots'></div>");
        },
        success: function(data) {
          $(".submit-newupdate-btn").removeClass('disabled');
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            $(".form-control-addupdate").append('<img class="temp_added_image" src="'+rootAsset+'/user_uploads/build_images/'+data.name+'.jpeg"><br><br>');
            $(".uploading-overlay").remove();
            var t = $('.form-control-addupdate');
            t.animate({"scrollTop": $('.form-control-addupdate')[0].scrollHeight}, "slow");
            t.focus();
            var sel = window.getSelection(), range = sel.getRangeAt(0);
            range.setStartBefore(t.children().last()[0]);
            sel.removeAllRanges();
            sel.addRange(range);
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
      if (width >= 800) {
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
    $.ajax({
      url: rootAsset+'saveUploadedImageEdit',
      type: 'post',
      cache: false,
      dataType: 'json',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $(".form-control-editupdate").append('<div class="uploading-overlay"></div>');
        $(".uploading-overlay").fadeIn();
        $(".uploading-overlay").text("Uploading Image...");
      },
      success: function(data) {
        $(".submit-newupdate-btn").removeClass('disabled');
        if(data.errors) {
          $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
        } else if (data.success) {
          $(".form-control-editupdate").append('<img class="temp_added_image" src="'+rootAsset+'/user_uploads/build_images/'+data.name+'.jpeg"><br><br>');
          $(".uploading-overlay").remove();
          var t = $('.form-control-editupdate');
          t.animate({"scrollTop": $('.form-control-editupdate')[0].scrollHeight}, "slow");
          t.focus();
          var sel = window.getSelection(), range = sel.getRangeAt(0);
          range.setStartBefore(t.children().last()[0]);
          sel.removeAllRanges();
          sel.addRange(range);
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
          } else if (data.no_access) {
            location.href = ""+rootAsset+"deniedaccess";
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
    var buildID = $("input[name=buildid]").val();
    $.ajax({
        url: rootAsset+'deletepost/'+postID+'/'+buildID,
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
          } else if (data.lastPost) {
            console.log('fefw');
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $('.follow-button-form').submit(function() {
    var userID = false;
    var rootAsset = $('.rootAsset').html();
    var buildID = $(".follow-button-form input[name=buildid]").val();
    if ($(".follow-button-form input[name=userid]").val() !== '') {
      userID = $(".follow-button-form input[name=userid]").val();
    }
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
              $(".follow-button-form .follow-button")
                .removeClass('btn-default user-isNot-following')
                .addClass('btn-success user-is-following')
                .html('Following <span class="glyphicon glyphicon-ok"></span>');
            } else if (data.unfollowing) {
              $(".follow-button-form .follow-button")
                .removeClass('btn-success user-is-following')
                .addClass('btn-default user-isNot-following')
                .html('Follow <span class="glyphicon glyphicon-heart"></span>');
            }
          } else if (data.success === false) {
            window.location.replace(""+rootAsset+"login");
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $('.build-filter-options').affix({
    offset: {
      top: 0,
      bottom: function () {
        //return (this.bottom = $('.footer').outerHeight(true))
      }
    }
  });

  $('.newcommentform').submit(function() {
    var $btn = $(".addcomment-btn");
    $btn.addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    var id = $("input[name=postid_addcomment]").val();
    $.ajax({
        url: rootAsset+'createcommentaction',
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
          $btn.val('Posting...');
        },
        success: function(data) {
          if(data.errors) {
            $('.modal-body').append('<div class="alert alert-danger centre-text modal-error-message" role="alert"><strong>Error!</strong> '+ data.errors +'</div>');
          } else if (data.success) {
            // success and does have access!
            $('#addCommentModal').modal('hide');
            $(".comments_"+id).append("<li><b>"+data.commenter+"</b>: "+data.comment+"</li>");
            $(".no-comments-message_"+id).fadeOut().remove();
            if (data.deleteOne) {
              //$(".comments_1640 li").first().remove();
            }
          } else if (data.no_access) {
            location.href = ""+rootAsset+"deniedaccess";
          }
          $btn.removeClass('disabled');
          $btn.val('Post Comment');
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".show-commentform-button").on('click', function(){
    $('#addCommentModal').modal('show');
    var id = this.id;
    $("input[name=postid_addcomment]").val(id);
  });

  $(".delete-comment").on('click', function(){
    /*var $btn = $(this);
    $btn.addClass('disabled');*/
    var rootAsset = $('.rootAsset').html();
    var id = this.id;
    $.ajax({
        url: rootAsset+'fetchonecomment/'+id,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          //$(".modal-error-message").remove();
          //$btn.text('Fetching...');
        },
        success: function(data) {
          if(data.errors) {
            // error message
          } else if (data.success) {
            $(".delete-comment-comment").text('"'+data.comment+'"');
            $(".do-delete-comment").attr('id', +data.commentid);
            $('#deleteCommentModal').modal('show');
          }
          //$btn.removeClass('disabled');
          //$btn.remove();
          //$(".comments_"+id).prepend('<button class="hide-added-comments">Hide added comments...</button>').hide().fadeIn();
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".do-delete-comment").on('click', function(){
    /*var $btn = $(this);
    $btn.addClass('disabled');*/
    var rootAsset = $('.rootAsset').html();
    var id = this.id;
    $.ajax({
        url: rootAsset+'deleteonecomment/'+id,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          //$(".modal-error-message").remove();
          //$btn.text('Fetching...');
        },
        success: function(data) {
          if(data.errors) {
            // error message
          } else if (data.success) {
            $('#deleteCommentModal').modal('hide');
            $('#comment-'+data.commentid).fadeOut(function(){
              $(this).remove();
              commentCountDelete = checkCommentCount(data.postid);
              if (commentCountDelete < 1) {
                $(".comments").append("<li class='no-comments-message_$post_id'>This post has no comments.</li>");
              }
            });
          }
          //$btn.removeClass('disabled');
          //$btn.remove();
          //$(".comments_"+id).prepend('<button class="hide-added-comments">Hide added comments...</button>').hide().fadeIn();
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });


  $(".dont-delete-comment").on('click', function(){
    $('#deleteCommentModal').modal('hide');
  });

  $(".show-all-comments-button").on('click', function(){
    var $btn = $(this);
    $btn.addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    var id = this.id;
    $.ajax({
        url: rootAsset+'fetchallcomments-forpost/'+id,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          $(".modal-error-message").remove();
          $btn.text('Fetching...');
        },
        success: function(data) {
          if(data.errors) {
            // error message
          } else if (data.success) {
            for(var i=0; i<data.comments.length; i++){
              var comment = data.comments[i];
              $(".comments_"+id).prepend("<li><b>"+comment.username+":</b> "+comment.comment+"</li>");
            }
          }
          $btn.removeClass('disabled');
          $btn.remove();
          //$(".comments_"+id).prepend('<button class="hide-added-comments">Hide added comments...</button>').hide().fadeIn();
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(".hide-added-comments").on('click', function(){
    console.log('dw');
  });

  function updatePostLikes($id){
    var rootAsset = $('.rootAsset').html();
    $.ajax({
      url: rootAsset+'getpostlikes/'+$id,
      type: 'post',
      cache: false,
      dataType: 'json',
      data: $(this).serialize(),
      success: function(data) {
        if (data.success) {
          // success
          $(".post-like-count-value_"+$id).html("<span class='glyphicon glyphicon-heart'></span> " +data.count+ " Likes");
        }
      },
      error: function(xhr, textStatus, thrownError) {
          alert('Something went to wrong.Please Try again later...');
      }
    });
    return false;
  }

  function checkCommentCount($id){
    var postid = $id;
    //console.log(postid);
    var length = $(".comments_"+postid+ " li").length;
    //console.log(length);
    return length;
  }

  $(document).on("click", ".like-post", function(){
    var $btn = $(this);
    $btn.addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    var id = this.id;
    var buildID = $(".buildID-hidden").text();
    $.ajax({
        url: rootAsset+'likepost/'+id+'/'+buildID,
        type: 'post',
        cache: false,
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function() {
          //$btn.text('Working...');
        },
        success: function(data) {
          if(data.errors) {
            // error message
          } else if (data.success) {
            if (data.notLogged) {
                location.href= rootAsset+'login';
            }
            $btn.removeClass('btn-success')
              .removeClass("like-post")
              .addClass('btn-primary')
              .addClass('unlike-post')
              .html("<span class='glyphicon glyphicon-thumbs-down'></span> Un-like");
            updatePostLikes(id);
          }
          $btn.removeClass('disabled');
        },
        error: function(xhr, textStatus, thrownError) {
            alert('Something went to wrong.Please Try again later...');
        }
    });
    return false;
  });

  $(document).on("click", ".unlike-post", function(){
    var $btn = $(this);
    $btn.addClass('disabled');
    var rootAsset = $('.rootAsset').html();
    var $id = this.id;
    $.ajax({
      url: rootAsset+'unlikepost/'+$id,
      type: 'post',
      cache: false,
      dataType: 'json',
      data: $(this).serialize(),
      success: function(data) {
        if (data.success) {
          $btn.removeClass('btn-default')
            .removeClass("unlike-post")
            .addClass('btn-success')
            .addClass('like-post')
            .html("<span class='glyphicon glyphicon-thumbs-up'></span> Like");
          updatePostLikes($id);
        }
        $btn.removeClass('disabled');
      },
      error: function(xhr, textStatus, thrownError) {
          alert('Something went to wrong.Please Try again later...');
      }
    });
    return false;
  });

});
