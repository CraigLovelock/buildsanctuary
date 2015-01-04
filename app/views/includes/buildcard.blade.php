<?php
$countBuilds = count($builds);

if (Auth::check()) {
	$userID = Auth::user()->id;
} else {
	$userID = false;
}

$path = '';

if ($countBuilds) {

  echo '<div id="builds" class="row">';

	foreach ($builds as $build)
	{
    $safeURLSlug = stringHelpers::safeURLSlug($build->blogtitle);
    $usernameOfOwner = User::usernameFromID($build->userid);
    $followStatus = User::followStatus($build->id, $userID);
    $viewStatus = User::viewStatus($build->id, $userID);
    $updatedStatus = User::updatedStatus($build->id, $userID);
    $followCount = Blog::countFollowers($build->id);
    $viewCount = Blog::countViews($build->id);
    $updateCount = Blog::countUpdates($build->id);
    $showBanner = "";
    if ($updatedStatus == 'true') {
    	$showBanner = '<div class="buildcard-banner-holder"><div class="buildcard-banner">Updated</div></div>';
    }
	  echo '
      <a href="viewbuild/'.$build->id.'/'.$safeURLSlug.'">
        <div class="item col-xs-12 col-sm-3">
        	'.$showBanner.'
          <div class="thumbnail">
            <img src="user_uploads/cover_images/'.$build->coverimage.'.jpeg">
            <div class="caption">
              <h5>'.$build->blogtitle.'</h5>
              <small>by: '.$usernameOfOwner.'</small>
            </div>
            <div class="buildcard-stats">
	            <ul>
	            	<li><span class="glyphicon glyphicon-heart-empty buildcard-follow-status-'.$followStatus.'" aria-hidden="true"></span> '.$followCount.'</li>
	            	<li><span class="glyphicon glyphicon-eye-open buildcard-follow-status-'.$viewStatus.'" aria-hidden="true"></span> '.$viewCount.'</li>
                <li><span class="glyphicon glyphicon-pencil buildcard-follow-status-'.$updatedStatus.'" aria-hidden="true"></span> '.$updateCount.'</li>
	            </ul>
            </div>
          </div>
        </div>
      </a>
    ';
	}

  echo '</div>';

} else {
    echo "
    <div class='row'>
      <div class='alert alert-danger centre-text not-full-width-alert' role='alert'>
        <b>No builds to show</b><br>
        You have not yet followed any builds.
      </div>
    </div>
    ";
}

?>