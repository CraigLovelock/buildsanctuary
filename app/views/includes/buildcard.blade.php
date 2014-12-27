<?php
$countBuilds = count($builds);

if (Auth::check()) {
	$userID = Auth::user()->id;
} else {
	$userID = false;
}

$path = '';

if ($builds) {

	foreach ($builds as $build)
	{
    $safeURLSlug = stringHelpers::safeURLSlug($build->blogtitle);
    $usernameOfOwner = User::usernameFromID($build->userid);
    $followStatus = User::followStatus($build->id, $userID);
    $viewStatus = User::viewStatus($build->id, $userID);
    $updatedStatus = User::updatedStatus($build->id, $userID);
    $showBanner = "";
    if ($updatedStatus == 'true') {
    	$showBanner = '<div class="buildcard-banner-holder"><div class="buildcard-banner">Updated</div></div>';
    }
	  echo '
      <a href="viewbuild/'.$build->id.'/'.$safeURLSlug.'">
        <div class="item col-sm-3">
        	'.$showBanner.'
          <div class="thumbnail">
            <img src="user_uploads/cover_images/'.$build->coverimage.'.jpeg">
            <div class="caption">
              <h5>'.$build->blogtitle.'</h5>
              <small>by: '.$usernameOfOwner.'</small>
            </div>
            <div class="buildcard-stats">
	            <ul>
	            	<li><span class="glyphicon glyphicon-heart-empty buildcard-follow-status-'.$followStatus.'" aria-hidden="true"></span> 336</li>
	            	<li><span class="glyphicon glyphicon-eye-open buildcard-follow-status-'.$viewStatus.'" aria-hidden="true"></span> 8.7k</li>
	            </ul>
            </div>
          </div>
        </div>
      </a>
    ';
	}

} else {
  echo "No builds returned";
  echo $countBuilds;
}

?>